var
    net = require('net'),
    MongoClient = require('mongodb'),
    ObjectID = MongoClient.ObjectID,
    MongoClient = MongoClient.MongoClient,
    io = require('socket.io')(6001, {
        origins : 'http://376174.msk-ovz.ru:*'
    }),
    request = require('request'),

    urlDB = "mongodb://127.0.0.2:27017",
    enabled = {},
    timeouts = {},
    carsTrips = {},
    latestCoordinates = {},
    campTimeouts = {},
    campEventTimeouts = {},
    isMoving = {},
    powerStatuses = {},

    Redis = require('ioredis'),
    redis = new Redis(6379, '127.0.0.2');
io.set('origins', 'http://376174.msk-ovz.ru:*');
//{path: '/sock/socket.io'}


//functions
function getDistanceInKm(lat1,lon1,lat2,lon2) {
    var R = 6371; // Radius of the earth in km
    var dLat = (lat2-lat1) * (Math.PI/180);  // deg2rad below
    var dLon = (lon2-lon1) * (Math.PI/180);
    var a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * (Math.PI/180)) * Math.cos(lat2 * (Math.PI/180)) *
        Math.sin(dLon/2) * Math.sin(dLon/2)
    ;
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c; // Distance in km
    return d;
}
//end

MongoClient.connect(urlDB, { useNewUrlParser: true }, function(err, client) {
    if (err) throw err;
    let db = client.db('track_cars');

    let movings = db.collection('movings'),
        events = db.collection('events'),
        trips = db.collection('trips'),
        cars = {};

    db.collection('cars').find({}).toArray(function (err, response) {
        response.map(function (car) {
            cars[car.imei] = car;
        });
    });

    io.on('connection', function (socket) {
        request.get({
                url : 'http://376174.msk-ovz.ru/getRoom',
                headers : {cookie : socket.request.headers.cookie},
                json : true
            }, function (error, response, json) {
                if (json && json.room) {
                    socket.join(json.room, () => {
                    });
                    console.log(json.room);
                } else {
                    socket.on('token', function (token) {
                        request.get({
                                url : 'http://376174.msk-ovz.ru/getRoom',
                                headers : {cookie : socket.request.headers.cookie},
                                auth : {
                                    'bearer' : token
                                },
                                json : true
                            }, function (error, response, json) {
                                if (json && json.room) {
                                    socket.join(json.room, () => {
                                    });
                                }
                            }
                        );

                    });
                }
            }
        );
    });


    var server = net.createServer(function (socket) {

        socket.on('data', function (data) {
            data = data.toString();
            console.log(data);
            data = data.split(',');
            if (data && data[16]) {

                let imei = data[1];
                let car = cars[imei];

                if (car) {

                    let rooms = [];
                    if (car.user_id) {
                        rooms.push(car.user_id);
                    }

                    let N = parseFloat(data[5]),
                        E = parseFloat(data[7]);

                    let lat = Math.floor(N/100)+((((N/100)-Math.floor(N/100))*100)/60),
                        lng = Math.floor(E/100)+((((E/100)-Math.floor(E/100))*100)/60);
                    console.log(lat, lng);

                    clearTimeout(timeouts[imei]);
                    timeouts[imei] = false;

                    if (latestCoordinates[imei] &&
                        !((Math.abs(lat) > Math.abs(latestCoordinates[imei][0] - 0.0007) &&
                            Math.abs(lat) < Math.abs(latestCoordinates[imei][0] + 0.0007)) &&
                            (Math.abs(lng) > Math.abs(latestCoordinates[imei][1] - 0.0007) &&
                                Math.abs(lng) < Math.abs(latestCoordinates[imei][1] + 0.0007)))) {
                        console.log('norm');

                        clearTimeout(campEventTimeouts[imei]);
                        campEventTimeouts[imei] = false;
                        clearTimeout(campTimeouts[imei]);
                        campTimeouts[imei] = false;

                        if (!isMoving[imei]) {

                            let date = new Date();
                            date = new Date(date.setHours(date.getHours() + 3));
                            let event = {
                                'type': 6,
                                'car_id': car._id.toString(),
                                'title': car.title,
                                'imei': imei,
                                'lat': lat,
                                'lng': lng,
                                'speed' : parseFloat(parseFloat(data[9]).toFixed(1)),
                                'course' : Number(data[10]),
                                'created_at': date
                            };

                            io.to(['admin']).to(rooms).emit('event', event);

                            events.insertOne(event, function (err, response) {
                                if (err) {
                                    throw err;
                                }

                                isMoving[imei] = true;
                            });
                        }


                        if (!carsTrips[imei]) {

                            let date = new Date();
                            date = new Date(date.setHours(date.getHours() + 3));
                            let trip = {
                                'car_id': car._id.toString(),
                                'created_at': date,
                                'ended_at': null,
                                'mileage': null,
                            };

                            io.to(['admin']).to(rooms).emit('newTrip', trip);

                            trips.insertOne(trip, function (err, response) {
                                if (err) {
                                    throw err;
                                }

                                carsTrips[imei] = response.ops[0]._id.toString();
                            });


                        }


                        let mileage = getDistanceInKm(lat, lng, latestCoordinates[imei][0], latestCoordinates[imei][1]);
                        console.log(mileage);
                        let date = new Date();
                        date = new Date(date.setHours(date.getHours() + 3));
                        let moving = {
                            'car_id': car._id.toString(),
                            'group_id': car.group_id ? car.group_id.toString() : null,
                            'imei': imei,
                            'trip_id': carsTrips[imei],
                            'lat': lat,
                            'lng': lng,
                            'speed' : isMoving[imei] ? parseFloat(parseFloat(data[9]).toFixed(1)) : 0.0,
                            'course' : Number(data[10]),
                            'mileage' : mileage,
                            'created_at' : date
                        };

                        io.to(['admin']).to(rooms).emit('moving', moving);
                        movings.insertOne(moving, function (err, response) {
                            if (err) {
                                throw err;
                            }
                        });

                        redis.get('laravel_database_trip:mileage:' + carsTrips[imei], function (err, response) {
                            if (err) {
                                throw err;
                            }

                            if (!response) {
                                response = 0;
                            }

                            response = Math.round(Number(response) + mileage, 10);

                            redis.set('laravel_database_trip:mileage:' + carsTrips[imei], response);
                        });

                        latestCoordinates[imei] = [lat, lng];
                    } else {
                        if (!latestCoordinates[imei]) {
                            latestCoordinates[imei] = [lat, lng];
                        }

                        console.log('stay');
                        if (!campEventTimeouts[imei] && isMoving[imei]) {
                            campEventTimeouts[imei] = setTimeout(function () {

                                let date = new Date();
                                date = new Date(date.setHours(date.getHours() + 3));
                                let event = {
                                    'type': 7,
                                    'car_id': car._id.toString(),
                                    'title': car.title,
                                    'imei': imei,
                                    'lat': lat,
                                    'lng': lng,
                                    'speed' : 0.0,
                                    'course' : Number(data[10]),
                                    'created_at': date
                                };

                                io.to(['admin']).to(rooms).emit('event', event);

                                events.insertOne(event, function (err, response) {
                                    if (err) {
                                        throw err;
                                    }

                                    isMoving[imei] = false;
                                });

                            }, 600000);
                        }


                        if (!campTimeouts[imei] && carsTrips[imei]) {
                            campTimeouts[imei] = setTimeout(function () {

                                redis.get('laravel_database_trip:mileage:' + carsTrips[imei], function (err, response) {
                                    if (err) {
                                        throw err;
                                    }

                                    if (!response) {
                                        response = 0;
                                    }

                                    let date = new Date();
                                    date = new Date(date.setHours(date.getHours() + 3));

                                    io.to(['admin']).to(rooms).emit('tripEnded', {
                                        'car_id': car._id.toString(),
                                        'ended_at': date,
                                        'mileage': response,
                                    });

                                    trips.updateOne({_id: new ObjectID(carsTrips[imei])}, {$set : {ended_at: date, mileage: response}}, function (err, response) {
                                        if (err) {
                                            throw err;
                                        }

                                        carsTrips[imei] = false;

                                        redis.del('laravel_database_trip:mileage:' + carsTrips[imei]);
                                    });

                                });

                            }, 1800000);
                        }

                    }



                    if (!enabled[imei]) {
                        enabled[imei] = true;

                        let date = new Date();
                        date = new Date(date.setHours(date.getHours() + 3));
                        let event = {
                            'type': 0,
                            'car_id': car._id.toString(),
                            'title': car.title,
                            'imei': imei,
                            'lat': lat,
                            'lng': lng,
                            'speed' : isMoving[imei] ? parseFloat(parseFloat(data[9]).toFixed(1)) : 0.0,
                            'course' : Number(data[10]),
                            'created_at': date
                        };
                        io.to(['admin']).to(rooms).emit('event', event);

                        events.insertOne(event, function () {
                            if (err) {
                                throw err;
                            }
                        });
                    }

                    if (!timeouts[imei]) {
                        timeouts[imei] = setTimeout(function () {
                            console.log('enabl');

                            let date = new Date();
                            date = new Date(date.setHours(date.getHours() + 3));
                            let event = {
                                'type': 1,
                                'car_id': car._id.toString(),
                                'title': car.title,
                                'imei': imei,
                                'lat': lat,
                                'lng': lng,
                                'speed' : isMoving[imei] ? parseFloat(parseFloat(data[9]).toFixed(1)) : 0.0,
                                'course' : Number(data[10]),
                                'created_at': date
                            };
                            io.to(['admin']).to(rooms).emit('event', event);

                            events.insertOne(event, function () {
                                if (err) {
                                    throw err;
                                }

                                enabled[imei] = false;
                            });


                            if (!campEventTimeouts[imei] && isMoving[imei]) {
                                campEventTimeouts[imei] = setTimeout(function () {

                                    let date = new Date()
                                    date = new Date(date.setHours(date.getHours() + 3));
                                    let event = {
                                        'type': 7,
                                        'car_id': car._id.toString(),
                                        'title': car.title,
                                        'imei': imei,
                                        'lat': lat,
                                        'lng': lng,
                                        'speed' : 0.0,
                                        'course' : Number(data[10]),
                                        'created_at': date
                                    };
                                    io.to(['admin']).to(rooms).emit('event', event);

                                    events.insertOne(event, function (err, response) {
                                        if (err) {
                                            throw err;
                                        }

                                        isMoving[imei] = false;
                                    });

                                }, 600000);
                            }


                            if (!campTimeouts[imei] && carsTrips[imei]) {
                                campTimeouts[imei] = setTimeout(function () {

                                    redis.get('laravel_database_trip:mileage:' + carsTrips[imei], function (err, response) {
                                        if (err) {
                                            throw err;
                                        }

                                        if (!response) {
                                            response = 0;
                                        }

                                        let date = new Date();
                                        date = new Date(date.setHours(date.getHours() + 3));

                                        io.to(['admin']).to(rooms).emit('tripEnded', {
                                            'car_id': car._id.toString(),
                                            'ended_at': date,
                                            'mileage': response,
                                        });
                                        console.log('update');
                                        trips.updateOne({_id: new ObjectID(carsTrips[imei])}, {$set: {ended_at: date, mileage: response}}, function (err, response) {
                                            if (err) {
                                                throw err;
                                            }

                                            carsTrips[imei] = false;

                                            redis.del('laravel_database_trip:mileage:' + carsTrips[imei]);
                                        });
                                    });
                                }, 1800000);
                            }
                        }, 45000);
                    }


                    let status = data[12];
                    let power = status.slice(4, 5);

                    if (power == '9' && !powerStatuses[imei]) {//если включена
                        powerStatuses[imei] = true;

                        let date = new Date();
                        date = new Date(date.setHours(date.getHours() + 3));
                        let event = {
                            'type': 4,
                            'car_id': car._id.toString(),
                            'title': car.title,
                            'imei': imei,
                            'lat': lat,
                            'lng': lng,
                            'speed' : isMoving[imei] ? parseFloat(parseFloat(data[9]).toFixed(1)) : 0.0,
                            'course' : Number(data[10]),
                            'created_at': date
                        };
                        io.to(['admin']).to(rooms).emit('event', event);

                        events.insertOne(event, function (err, response) {
                            if (err) {
                                throw err;
                            }
                        });

                    } else if (power == 'B' && powerStatuses[imei]) {//если выключена
                        powerStatuses[imei] = false;

                        let date = new Date();
                        date = new Date(date.setHours(date.getHours() + 3));
                        let event = {
                            'type': 5,
                            'car_id': car._id.toString(),
                            'title': car.title,
                            'imei': imei,
                            'lat': lat,
                            'lng': lng,
                            'speed' : isMoving[imei] ? parseFloat(parseFloat(data[9]).toFixed(1)) : 0.0,
                            'course' : Number(data[10]),
                            'created_at': date
                        };
                        io.to(['admin']).to(rooms).emit('event', event);

                        events.insertOne(event, function (err, response) {
                            if (err) {
                                throw err;
                            }
                        });
                    }


                } else {
                    console.log('car not found');
                }

            }
        });

        socket.on("error", function (data) {
        });

    });

    server.listen(2000);


});

//
redisSubscribed = new Redis(6379, '127.0.0.2');
    redisSubscribed.psubscribe('*', function (error, count) {
//...
});

redisSubscribed.on('pmessage', function (pattern, channel, data) {
    data = JSON.parse(data);
    data = data.data.data;

    if (data.type == 2 || data.type == 3) {
        io.to(['admin']).emit('event', data);
    }
});

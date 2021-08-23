<?php

if (!is_readable(__DIR__ . '/../vendor/autoload.php')) {
    die('Please run composer install first');
}

require __DIR__ . '/../vendor/autoload.php';

\Craftorio\Authserver\Authserver::start();

//
//Flight::route('POST /authenticate', static function () {
//    // {
//    //    "agent": {
//    //        "name": "Minecraft",
//    //        "version": "1"
//    //    },
//    //    "clientToken": "8776fc7f22a245c1a5dbd6b6ecd236ad",
//    //    "password": "$erGGiO1Exception",
//    //    "requestUser": "true",
//    //    "username": "sergey@cherepanov.org.ua"
//    //}
//
//    //{
//    //    "accessToken": "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIzYjhlOTQyNzg5ODM0OTc0OGU3OWY4OWI1NzNhMmE4YSIsInlnZ3QiOiI4NTQzNjhkYTBkZDc0NjhjODQ0MTMwMjhmYjJkOGU4ZCIsInNwciI6ImE5NzYwMGJlYTEyZTQ1YWZiMjExZmU3NzliMGExMjkwIiwiaXNzIjoiWWdnZHJhc2lsLUF1dGgiLCJleHAiOjE2Mjk4MjM5NzksImlhdCI6MTYyOTY1MTE3OX0.X6kmme2d6FrLc9JR5kEHynUO6xPEEDBGC-SH8hejs98",
//    //    "availableProfiles": [
//    //        {
//    //            "id": "a97600bea12e45afb211fe779b0a1290",
//    //            "name": "MisterChe"
//    //        }
//    //    ],
//    //    "clientToken": "8776fc7f22a245c1a5dbd6b6ecd236ad",
//    //    "selectedProfile": {
//    //        "id": "a97600bea12e45afb211fe779b0a1290",
//    //        "name": "MisterChe"
//    //    },
//    //    "user": {
//    //        "id": "3b8e9427898349748e79f89b573a2a8a",
//    //        "username": "sergey@cherepanov.org.ua"
//    //    }
//    //}
//
//    //{
//    //    "error": "ForbiddenOperationException",
//    //    "errorMessage": "Invalid credentials. Invalid username or password."
//    //}
//});
//
//Flight::route('POST /session/minecraft/join', static function () {
//    //{"accessToken":"eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIzYjhlOTQyNzg5ODM0OTc0OGU3OWY4OWI1NzNhMmE4YSIsInlnZ3QiOiJjNGU1MWZmYmI1NDQ0Mjk0OTkzMzJlYWFiZjk0N2NiYyIsInNwciI6ImE5NzYwMGJlYTEyZTQ1YWZiMjExZmU3NzliMGExMjkwIiwiaXNzIjoiWWdnZHJhc2lsLUF1dGgiLCJleHAiOjE2Mjk4MTU5ODgsImlhdCI6MTYyOTY0MzE4OH0.r-kY0DSCoSfijRwTB0RBs9JIRc75FgMJFBwSWoePMLE","selectedProfile":"a97600bea12e45afb211fe779b0a1290","serverId":"6b87dd823d3a2c3c3165d248013f8e11b07955cc"}
//    $payload = Flight::request()->data;
//    $validator = new \JsonSchema\Validator();
//    $validator->validate(
//        $payload, (object)[
//            "type" => "object",
//            "properties" => (object)[
//                "accessToken" => (object)[
//                    "type" => "string"
//                ],
//                "selectedProfile" => (object)[
//                    "type" => "string"
//                ],
//                "serverId" => (object)[
//                    "type" => "string"
//                ]
//            ]
//        ],
//        \JsonSchema\Constraints\Constraint::CHECK_MODE_COERCE_TYPES
//    );
//});
//
//Flight::route('GET /session/minecraft/hasJoined', static function () {
//    $serverId = Flight::request()->query['serverId'];
//    $username = Flight::request()->query['username'];
//});
//
//Flight::start();
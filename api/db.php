<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

function jsonResponse(Response $response, mixed $data, int $status = 200): Response{
    $response->getBody()->write(json_encode($data));
    return $response
        ->withHeader("Content-Type", "application/json")
        ->withStatus($status);
}

function getClasses(Response $response, PDO $db): Response{
    $statement = $db->query("SELECT * FROM class ORDER BY name");
    $class = $statement->fetchAll();

    return jsonResponse($response, ["classes" => $class]);
}

function getClass(Response $response, PDO $db, int $id): Response{
    $statement = $db->prepare("SELECT * FROM class WHERE id = :id");
    $statement->execute(["id" => $id]);
    $class = $statement->fetch();

    if(!$class){
        return jsonResponse($response, ["error"=> "Class not found"], 404);
    }
    return jsonResponse($response, $class);
}

function findByName(Response $response, PDO $db, string $name): Response{
    $statement = $db->prepare("SELECT * FROM class WHERE name LIKE :name ORDER BY name");
    $statement->execute(["name"=> "%" . $name ."%"]);
    $class = $statement->fetchAll();

    return jsonResponse($response, ["classes"=> $class]);
}

function findByProfessor(Response $response, PDO $db, string $professor): Response{
    $statement = $db->prepare("SELECT * FROM class WHERE professor LIKE :professor ORDER BY professor");
    $statement->execute(["professor"=> "%" . $professor ."%"]);
    $class = $statement->fetchAll();

    return jsonResponse($response, ["classes"=> $class]);
}

function findByRoom(Response $response, PDO $db, string $room): Response{
    $statement = $db->prepare("SELECT * FROM class WHERE room LIKE :room ORDER BY room");
    $statement->execute(["room"=> "%" . $room ."%"]);
    $class = $statement->fetchAll();

    return jsonResponse($response, ["classes"=> $class]);
}

function findByType(Response $response, PDO $db, string $type): Response{
    $statement = $db->prepare("SELECT * FROM class WHERE type LIKE :type ORDER BY type");
    $statement->execute(["type"=> "%" . $type ."%"]);
    $class = $statement->fetchAll();

    return jsonResponse($response, ["classes"=> $class]);
}

function addClass(Request $request, Response $response, PDO $db): Response {
    $class = (array) $request->getParsedBody();

    $name = trim($class["name"] ?? "");
    $professor = trim($class["professor"] ?? "");
    $room = trim($class["room"] ?? "");
    $start_time = trim($class["start_time"] ?? "");
    $end_time = trim($class["end_time"] ?? "");
    $day = ($class["day"] ?? "") !== "" ? (int) $class["day"] : null;
    $type = trim($class["type"] ?? "");
    $description = trim($class["description"] ?? "");

    if ($name === "") {
        return jsonResponse($response, ["error" => "Class name is required"], 400);
    }

    $statement = $db->prepare(
        "INSERT INTO class (name, professor, room, start_time, end_time, day, type, description)
         VALUES (:name, :professor, :room, :start_time, :end_time, :day, :type, :description)"
    );

    $statement->execute([
        "name" => $name,
        "professor" => $professor,
        "room" => $room,
        "start_time" => $start_time,
        "end_time" => $end_time,
        "day" => $day,
        "type" => $type,
        "description" => $description
    ]);

    $class["id"] = (int) $db->lastInsertId();

    return jsonResponse($response, $class, 201);
}

function updateClass(Request $request, Response $response, PDO $db, int $id): Response
{
    $check = $db->prepare("SELECT id FROM class WHERE id = :id");
    $check->execute(["id" => $id]);

    if (!$check->fetch()) {
        return jsonResponse($response, ["error" => "Class not found"], 404);
    }

    $class = (array) $request->getParsedBody();

    $name = trim($class["name"] ?? "");
    $professor = trim($class["professor"] ?? "");
    $room = trim($class["room"] ?? "");
    $start_time = trim($class["start_time"] ?? "");
    $end_time = trim($class["end_time"] ?? "");
    $day = ($class["day"] ?? "") !== "" ? (int) $class["day"] : null;
    $type = trim($class["type"] ?? "");
    $description = trim($class["description"] ?? "");

    if ($name === "") {
        return jsonResponse($response, ["error" => "Class name is required"], 400);
    }

    $statement = $db->prepare(
        "UPDATE class
        SET name = :name, professor = :professor, room = :room, start_time = :start_time,
            end_time = :end_time, day = :day, type = :type, description = :description
            WHERE id = :id"
    );

    $statement->execute([
        "id" => $id,
        "name" => $name,
        "professor" => $professor,
        "room" => $room,
        "start_time" => $start_time,
        "end_time" => $end_time,
        "day" => $day,
        "type" => $type,
        "description" => $description
    ]);

    $class["id"] = $id;
    return jsonResponse($response, $class);
}

function deleteClass(Response $response, PDO $db, int $id): Response
{
    $check = $db->prepare("SELECT id FROM class WHERE id = :id");
    $check->execute(["id" => $id]);

    if (!$check->fetch()) {
        return jsonResponse($response, ["error" => "Class not found"], 404);
    }

    $statement = $db->prepare("DELETE FROM class WHERE id = :id");
    $statement->execute(["id" => $id]);

    return jsonResponse($response, ["message" => "Class deleted"],200);
}
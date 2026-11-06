# Web Development 3 - Project 1 Part 1


## Choices

- Docker 
- PHP + Slim 4

## Set Up

To set up the project:

- Open docker

- In VS code type in the terminal:
```powershell
docker compose up -d --build
```
- In MySQL WorkBench create a new connection
- Give the connection a name
- Under parameter make the hostname `127.0.0.1` and Port `3308`
- Create the connection the password and username are both `root`
- In MySQL run the database.sql file
- The project is now set up the url for the api is:
```
http://localhost:8080/api/
```

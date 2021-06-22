# table Schema

## profile
### all of the api function create, read, search, update, delete is reflected in this table 
CREATE TABLE IF NOT EXISTS profile(
	id SERIAL PRIMARY KEY,
	first_name VARCHAR(255) NOT NULL,
	last_name VARCHAR(255) NOT NULL,
	middle_name VARCHAR(255),
	gender VARCHAR (255),
        birth_date DATE,
  	date_created  timestamp,
	date_update timestamp
);

## api user
#for you to use the 5 endpoints of the api you must have an api credentials
#you can create api credentials in  simulation file cli.create.api.user.php

CREATE TABLE IF NOT EXISTS api_user(
	id SERIAL PRIMARY KEY,
	username VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	password_encrypted VARCHAR(255) NOT NULL,
  	date_created  timestamp,
	date_update timestamp
);

# Api Functions/Endpoints

## create / add profile record
api url: http://imroland.com/exam/create/
method: Post
parameters: 
	username: "Api Username"
	password: "Api Password"
	first_name:
	last_name:
	middle_name:
	birth_date:
	gender:
Response:
{
    "status": "Success",
    "message": "Record created"
}

## update / update or edit profile record
api url: http://imroland.com/exam/update/{id of record}
example url: api url: http://imroland.com/exam/update/18
method: Post
parameters: "You just need to pass what field you want to edit it can be multiple field."
	username: "Api Username"
	password: "Api Password"
	first_name: "new first name"
Response:
{
    "status": "Success",
    "message": "Affected rows 1",
    "id": "18"
}

## delete record
api url: http://imroland.com/exam/delete/{id of record}
example url: http://imroland.com/exam/delete/87
method: Post
parameters: 
	username: "Api Username"
	password: "Api Password"
Response:
{
    "status": "Success",
    "message": "Delete Success",
    "id": "87"
}

## read/ read or select specific record based on id
api url: http://imroland.com/exam/read/{id of record}
example url: http://imroland.com/exam/read/18
method: Post
parameters: 
	username: "Api Username"
	password: "Api Password"
Response:
{
    "status": "Success",
    "data": [
        {
            "id": 18,
            "first_name": "Roy",
            "last_name": "valess",
            "middle_name": "",
            "gender": "Male",
            "birth_date": "1992-01-11",
            "date_created": "2021-06-21 23:37:14",
            "date_update": "2021-06-23 03:12:54"
        }
    ]
}

## search/ view or search record based on passed parameters
```bash
api url: http://imroland.com/exam/search
method: Post
parameters: "You just need to pass what field you want to search. if you dont pass it will show all"
	username: "Api Username"
	password: "Api Password"
	first_name: "Rol"
{
    "status": "Success",
    "data": [
        {
            "id": 88,
            "first_name": "John",
            "last_name": "Doe",
            "middle_name": "Test",
            "gender": "Male",
            "birth_date": "1992-01-11",
            "date_created": "2021-06-23 02:40:11",
            "date_update": "2021-06-23 02:40:11"
        },
        {
            "id": 89,
            "first_name": "John",
            "last_name": "Doe",
            "middle_name": "Test",
            "gender": "Male",
            "birth_date": "1992-01-11",
            "date_created": "2021-06-23 02:43:11",
            "date_update": "2021-06-23 02:43:11"
        },
        {
            "id": 18,
            "first_name": "Roy",
            "last_name": "valess",
            "middle_name": "",
            "gender": "Male",
            "birth_date": "1992-01-11", 
            "date_created": "2021-06-21 23:37:14",
            "date_update": "2021-06-23 03:12:54"
        }
    ]
}```

## Test URL
http://imroland.com/exam/test/

## postman collection
https://www.getpostman.com/collections/d5a0e2461767adb1e2fe
	
	

	 





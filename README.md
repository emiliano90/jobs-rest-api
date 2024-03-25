# Laravel 11 REST APi for Jobs service

This API is created using Laravel 11 API Resource. It has Job, Skill and Subscriber.

#### Following are the Models

Job  
Skill  
Subscriber

#### Usage

Clone the project via `git clone` or download the zip file.

##### .env

Project works with SQLITE

Run Migration
Run the following command to create migrations in the databbase.

`php artisan migrate`

#### API EndPoints

##### Job

Job GET All http://localhost:8000/api/v1/jobs  
Job GET Single http://localhost:8000/api/v1/jobs/1  
Job POST Create http://localhost:8000/api/v1/jobs  
Job PUT Update http://localhost:8000/api/v1/jobs/1  
Job DELETE destroy http://localhost:8000/api/v1/jobs/1

Params for filter jobs:  
optionals: name, salary_min, salary_max, country  
Params for add data from external source  
external_src = true

##### Skill

Skill GET All http://localhost:8000/api/v1/skills  
Skill GET Single http://localhost:8000/api/v1/skills/1  
Skill POST Create http://localhost:8000/api/v1/skills  
Skill PUT Update http://localhost:8000/api/v1/skills/1  
Skill DELETE destroy http://localhost:8000/api/v1/skills/1

Params for POST skill:  
name

##### Job skills

Job skills GET http://localhost:8000/api/v1/jobs/{job}/skills  
Job skills GET All http://localhost:8000/api/v1/jobs/{job}/skills  
Job skills GET Single http://localhost:8000/api/v1/jobs/{job}/skills/1  
Job skills POST Create http://localhost:8000/api/v1/jobs/{job}/skills  
Job skills DELETE destroy http://localhost:8000/api/v1/jobs/{job}/skills/1

##### Subscribe to notification

Subscriber POST Create http://localhost:8000/api/v1/subscribe  
Params:  
mail  
optionals: job_name, job_salary_min, job_salary_max, job_country

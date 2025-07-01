# Recruitis.io job apply

## Installation
```bash
docker compose up -d # run docker container
docker compose exec apache.recruitis npm ci # install frontend dependencies
docker compose exec apache.recruitis npm run build # build Vue app
docker compose exec apache.recruitis composer install # install backend dependencies
```
The application will be available at http://localhost:3030

Also do not forget to create a `.env.local` file with the following content:
```
RECRUITIS_API_TOKEN=your_personal_api_token
```

# Set up 
```
git clone git@github.com:karlisFreimanis/md202512-yii.git 
```
```
cp -a .env.example .env
```
```
docker compose up -d 
```
```
docker exec -it 202512-kf-md-php /bin/sh
```
```
composer install 
```
```
./yii migrate
```
## Demo 
Look for file -> demo.mp4

# Tasks
1. Setup project
2. Build db structure and switch login to db
3. Crud for users
4. Add permissions to controllers
5. Make user view reusable
6. Show data based on role
7. set up readme

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
8. cleanup written code
9. add db bak

# short summary
Task is simple. Same time nice reusable solution is definitely too long for quick md. My result is not good. First using foreign framework adds extra time, even if I know what to expect from MVC php framework it takes to find everything. Another thing setting everything up from scrap take reasonable amount of time.

# after comments
 - API part is missing, but it shouldn't be that complicated
 - - detect user that is linked to access card, is user deleted or access car is not expired. 
 - - check if user has any tasks in given period at this specific construction site.
 - - if scaling required index tasks by dates
 - Current solution scaling is 0, pagination and limits is bare minimum
 - I wouldn't use templates. I would use FE framework.
 - In real project I would use many to many user roles, i just didn't want to deal with UI.
 - Using access level to display, construction sites for managers, is likely not what was planned. It would be just chore to add new link to users
 - I tried to use yii console, but on basic setup it didn't help too much, there may be some generated data that i didn't clen up.
 - I pick up basic version from website. I noticed too late it don't fit php 8.4 to well, so I didn't bother to force strict type everywhere as vendor is outdated.
 - Focused to much on reusability, didn't do it well and lost some time that could be spared with more brutal and complete solution. 

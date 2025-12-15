# Set up 
```
https://github.com/karlisFreimanis/md202512-yii.git
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
1. Set up the project
2. Build db structure and switch the login to db
3. Crud for users
4. Add permissions to controllers
5. Make the user view reusable
6. Show data based on role
7. Set up the README
8. cleanup written code
9. add db bak

# short summary
The task is simple. Same time nice reusable solution is definitely too long for quick md. My result is not good. First, using a foreign framework adds extra time, even if I know what to expect from the MVC PHP framework, it takes time to find everything. Another thing setting everything up from scratch takes a reasonable amount of time.

# after comments
 - The API part is missing, but it shouldn't be that complicated
 - - detect the user that is linked to the access card, if the user has been deleted or the access card has not expired. 
 - - check if the user has any tasks in the given period at this specific construction site.
 - - if scaling required index tasks by dates
 - Current solution scaling is 0, pagination and limits are bare minimum
 - I wouldn't use templates. I would use the FE framework.
 - In a real project, I would use many-to-many user roles; I just didn't want to deal with UI.
 - Using the access level to display construction sites for managers is likely not what was planned. It would be just a chore to add a new link to users
 - I tried to use Yii console, but on the basic setup, it didn't help too much; there may be some generated data that I didn't clean up.
 - I pick up the basic version from the website. I noticed too late that it doesn't fit PHP 8.4 too well, so I didn't bother to force strict type everywhere, as the vendor is outdated.
 - Focused too much on reusability, didn't do it well and lost some time that could be spared with a more brutal and complete solution.
 - Technically manager could hack the system by creating tasks for employees that they don't manage. Fixing that wouldn't be too hard, just an override TaskRepository.php, create/update and filter options for select based on manager_id

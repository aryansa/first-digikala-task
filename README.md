# Install

Run:
```
git clone https://github.com/aryansa/first-digikala-task.git
```

Open the folder and run:
```
docker-compose up
```

Wait for `composer update`, then create a database:
```
# root:root@localhost:3307
CREATE DATABASE digikala;
```

Then open this url in browser:
```
http://localhost:9092/install/
```

## Admin
```
Email: admin@digikala.com
Password: 1234
```
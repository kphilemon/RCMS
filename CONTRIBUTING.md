# RCMS Workflow
To contribute, please follow the workflow below. **Only** push directly to the `master` branch if you know exactly what you're doing. Evaluate the impact of change before doing so. 

1. Make sure your local master is up-to-date.

    ```
    git checkout master
    git pull origin master
    ```
2. Create a new local branch from your master branch to work on your changes.
    ```
    git checkout -b <my-feature-branch>
    ```
3. Make changes on your new branch. Add. Commit. Repeat.
    ```
    git add .
    git commit -m "A meaningful commit message"
    ```
4. When you are ready to push your branch, make sure it's up-to-date with the remote master first.
    ```
    git checkout master
    git pull origin master
    git checkout <my-feature-branch>
    git rebase master
    ```
5. Resolve conflicts if there's any using the IDE's tool.
    https://www.jetbrains.com/help/idea/resolving-conflicts.html

6. Make sure there's no more conflict and you're now ready to push your changes to the remote repository.
    ```
    git push origin <my-feature-branch>
    ```
7. Open a merge request on github to merge your branch to master.
    https://help.github.com/en/github/collaborating-with-issues-and-pull-requests/creating-a-pull-request

## Project Folder Structure
The folder structure for this project:

```
root/
├── config/             # configuration files
│   └── config.php
├── mysql/              # database files
│   └── setup.sql
├── public/             # web root
│   ├── assets/
│   │   ├── css/
│   │   ├── img/
│   │   └── js/
│   ├── .htaccess
│   └── index.php
├── src/                # PHP source codes
│   ├── api/            
│   ├── models/ 
│   ├── modules/ 
│   ├── templates/ 
│   └── utilities/ 
├── uploads/            # user private uploads (gitignored)
├── tests/              # test files
├── vendor/
├── .gitignore
├── composer.json
└── README.md
```

References:
- https://github.com/php-pds/skeleton
- https://www.nikolaposa.in.rs/blog/2017/01/16/on-structuring-php-projects/
- https://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873
- https://stackoverflow.com/questions/1387547/what-is-the-most-scalable-php-based-directory-structure-for-a-large-site

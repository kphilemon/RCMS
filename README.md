# Residential College Management System (RCMS)

A web application to help the students at residential college to register new
account, view and register for the activities organised by the residential college,
report an issue found at residential college, order food and apply for accommodation.

## Workflow
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
The folder structure for this particular phase (A simple html web application):

```
root/
├── css/
├── img/
├── js/
├── scripts/
├── common/
│   ├── navbar.html
│   ├── header.html
│   └── footer.html
├── index.html
├── other_pages.html
├── README.md
└── .gitignore
```

References:
- https://medium.com/@nmayurashok/file-and-folder-structure-for-web-development-8c5c83810a5
- https://www.htmlquick.com/tutorials/organizing-website.html
# Question 3 - Deployment Process for a Python CLI Application

Since the application is a Python CLI tool used by hundreds of employees across multiple teams, my focus would be on making installation, updates, and version management as simple as possible.

I would package the application as a Python package and publish it to an internal package repository (similar to a private PyPI). This ensures that all users install approved versions of the tool and that releases are centralized.

The deployment process would be automated through a CI/CD pipeline. Whenever a new version is released, the pipeline would:

1. Run automated tests
2. Build the package
3. Publish the package to the internal repository
4. Create a new versioned release

To avoid dependency conflicts on users' machines, I would distribute the application using `pipx`, which installs CLI applications in isolated environments.

Example installation:

```bash
pipx install my-cli
```

For versioning, I would follow Semantic Versioning:

```text
1.0.0 -> Initial release
1.1.0 -> New features
1.1.1 -> Bug fixes
2.0.0 -> Breaking changes
```

To simplify upgrades, users could update the application through a command such as:

```bash
pipx upgrade my-cli
```

If a problem is discovered after a release, rollback is straightforward because previous versions remain available in the repository:

```bash
pipx install my-cli==1.1.0
```

This approach provides a standardized installation process, centralized version control, simple upgrades, and fast rollback capabilities, making it suitable for a CLI application used continuously by hundreds of users across the company.

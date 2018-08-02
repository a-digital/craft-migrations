# Craft Migrations

Migration Files for a fresh install of Craft 3 to quickly create a new project

![Logo](./icon.svg =250x)

## Requirements

These files require Craft CMS 3.0.0-beta.23 or later.

## Installation

Upload the migrations folder to your Craft 3 project at the same level as your templates and plugins folders.

Run them under Utilities => Migrations to create a default set of fields, volumes, and plugins in your project. Or you can run the following command:
```
./craft migrate/up
```

To revert them, use the following command for each file:
```
./craft migrate/down
```

## Customisation

Please also feel free to use these as a starting point to write your own custom migration files.
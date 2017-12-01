# RstTableTool
 
## Purpose

The script creates RST tables by path and saves the table to some file.

## Features

The only command provided is `rst:table:generate` which allows to use the following set of parameters:

| Parameter                                     | Type         | Description                                                                           |
| --------------------------------------------- | --------     | ------------------------------------------------------------------------------------- |
| --path=PATH                                   | **Required** | Path to directory files should be included to RST table                               |
| --output-file=OUTPUTFILE                      | **Required** | File to save RST table                                                                |
| --excluded-path-list=EXCLUDEPATHLIST          | **Optional** | List of strings paths( separated by whitespaces) that contain them should be excluded |

## Usage

```bash
php rst-table-tool.php rst:table:generate -- /Users/user/Projects/rst-table-builder table.log vendor table.log composer
```

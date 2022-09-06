# Spreadsheet - Coding Task

## Technologies used
* PHP 8.1.1
* Symfony 6.2
* PHPUnit 9
* PHPStan 1.8
* php-cs-fixer
* Docker

## How to use?

You will need Docker installed on your machine in order to start the application.

### Initial configuration
To start with, generate your Google API service account credentials json file and put it into `<project_dir>/secrets`.
Default credentials filename is `google.json`. 
If you want to use different filename you can configure it by overriding the `GOOGLE_API_CREDENTIALS_PATH` ENV variable. In order to do this create a local copy of a `.env` file (`cp .env .env.local`) and set the new value there.

# Startup
The application is designed to be an executable docker container meaning you can execute one operation at a time each time having a new temporary container instance created.

In order to simplify the access to the most commonly used functionalities an entrypoint executable file `spreadsheet` and a number of scripts under `bin` directory were created.

### Running the command
The command execution configuration is `./spreadsheet spreadsheet-upload [--destination=DESTINATION] [--data-type=DATA_TYPE] [--target-filename=TARGET_FILENAME] [-v] <file_source>`

By default, the command will use an `XML` data type and will publish it at `google_sheets` destination so you can amend those options as well as the `target_filename`.

You can add a `-v` flag in order to increase the command verbosity. By default, you will only see the most important messages. Increasing the verbosity will allow you to see all the intermediate steps details in the process.

You can use different file source types and formats as long as they are enabled in the application configuration (will come back to that later) and they are valid.

For example:

`./spreadsheet spreadsheet-upload -v /app/data/test.xml`

`./spreadsheet spreadsheet-upload -v data/test.xml`

`./spreadsheet spreadsheet-upload -v file:///app/data/test.xml`

`./spreadsheet spreadsheet-upload -v ftp://user:password@host/data/test.xml`

`./spreadsheet spreadsheet-upload -v http://www.hostname.site/data/test.xml`

`./spreadsheet spreadsheet-upload -v https://hostname.site/data/test.xml`

Or with different data types and destinations:

`./spreadsheet spreadsheet-upload -v --data-type=json https://hostname.site/data/test.json`

`./spreadsheet spreadsheet-upload -v --destination=some_destination --data-type=yaml ftp://user:password@host/data/test.yaml`


### Running tests

The application code is covered (unfortunately just partially) with unit and functional tests powered by PHPUnit 9.

In order to run tests you can use the following command:

`./spreadsheet phpunit`

### Running static analysis

The application code is enabled with a PHPStan static analysis tool with a full support of it's level 8 checks (https://phpstan.org/user-guide/rule-levels).

In order to run the analysis you can use the following command:

`./spreadsheet phpstan-analyse`

### Running php-cs-fixer

php-cs-fixer is configured to check the code style with a set of rules extended with `Symfony` and `Symfony:risky` rule groups.

In order to run the dry-run mode inspection you can use the following command:

`./spreadsheet php-cs-check`

### Rebuilding the image

In case you have made changes to the code or configurations and wish to force rebuild the docker image you can use the build script by running `./build`

## Comments and considerations

Unfortunately I had really few time to work on the task (just the weekend and a couple of nights during the week) which for me seems to not be enough to reach perfection :)
This is why I had to choose between attempting to implement the task logic itself deeper and spending more time on the application design, configurability and extensibility. 
So now I want to just share why did I choose the second option.

There is a number of features which are honestly missing from the application right now and which I would for sure implement if only I had more time:
1. Google Sheet availability: by default Google Sheets API when creating a new sheet with a service account makes it available for this account only. And since it's technically impossible to impersonate yourself with this account, there is no way to view the spreadsheet. I totally understand that it is really frustrating but solving this would rather require integrating Drive API to change the access level or forcing the command to update an existing shared sheet instead of creating a new one. To be honest I don't really like either of those options and this is why, having no time left, I've decided to leave this as is for now. 
2. Google API rate limits handling: PHP Google API client lib used is unfortunately not capable of handling rate limits properly. And considering that handling big files with the command would require sending it in batches, this can be a potential cause of rate limits being exceeded. This is something I would take care of for sure.
3. Input data validation: obviously not any XMl document can be represented as a spreadsheet. Invalid nesting level, handling of the properties set mismatch between the items, more then one root node types, etc. In order to do that a lot of time should be dedicated to the input validation in order not to allow incorrect data reach the Google API since considering the batching it might cause the problems like partial data upload and many others.
4. Grid headers extraction.
5. Big files handling: considering that XML files can potentially be really heavy it is required to rather set some limitations of the file size or find another way to stream the file contents instead of loading it all into the memory blindly and then attempting to use serializer on it causing the app to instantly run out of memory.
6. Tests coverage: right now only the most important parts of the code were covered with tests. Of course ideally the coverage should be higher.

Unfortunately this is something I had to sacrifice for the sake of time and somehow decent application design, and I feel like solving all of those within the time I had would not be possible anyway.

There is no limit of perfection, this task can be done within one evening, or it can take weeks depending on how well-designed and detailed you want it to be. 
But within a limited time frame the best thing you can do is to decide what are the most important features you can fit into it and do your best to meet the deadline.

## How it works

The application was designed with a purpose of maximum flexibility and extensibility. 
This is why the whole flow was separated into 3 steps:
1. File content loading
2. Data parsing
3. Spreadsheet publishing

Each step is reflected with an interface: 

* content loading -> `DataLoaderInterface`
* parsing -> `DataParserInterface`
* publishing ->  `SpreadsheetPublisherInterface`

Based on that you can support any custom input data source (from SQL DataBase to faker-generated data), any data type or encoding (Base64, CSV, etc) and any target location (Office 365 Excel, Excel file, etc.).
Based on the input configuration the application fetches the required services implementing those interfaces and composes them into a strategy whih is then executed.
This gives maximum configurability allowing you to use any combination of source-destination-data-type while keeping the code clean.

`config/services.yaml` file contains configuration parameters allowing you to choose which data types, sources and target locations to choose: 
* `spreadsheet.configuration.allowed_sources`
* `spreadsheet.configuration.allowed_destinations`
* `spreadsheet.configuration.allowed_data_types`

Those configurations are referencing the three enums cases: `FileSource`, `FileDestination`, `DataType`

Those same enums cases wrapped into `DataLoader`, `SpreadsheetPublisher` and `DataParser` attributes accordingly allow to map the concrete service to a certain source, destination or a data type.

Using data types as an example the process of extending the functionality is really simple:

1. Create a new class implementing `DataParserInterface` and configure the data parsing in it.
2. Add a new case representing this type into the `DataType` enum
3. Add a `DataParser` attribute to your new class and pass the new `DataType` case into it as an argument
4. Whitelist your new data type by adding it to the `spreadsheet.configuration.allowed_data_types` parameter.

And you are all set to start using your new data type parser with the command.

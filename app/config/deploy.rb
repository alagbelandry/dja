set :application, "auportdadjame"
set :domain,      "auportdadjame.com"
set :deploy_to,   "/var/www/auportdadjame.com"

set :repository,  "git@github.com:alagbelandry/dja.git"
set :scm,         :git

set :model_manager, "propel"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

set :shared_files,      ["app/config/parameters.ini"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors, true
set :use_composer, true

set :user, "auportda"
set :passsword, "L2NWaWfd"

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL
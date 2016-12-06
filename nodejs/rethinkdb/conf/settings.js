exports.rethinkdbHost = 'localhost';
exports.rethinkdbPort = 28015;
exports.rethinkdbDB = 'tv_shows';
exports.rethinkdbUser = "admin";
exports.rethinkdbPasswd = "evedatacenterT#%&.181";


var fs = require('fs');
var fn = "conf/local_settings.json";
var local_settings = JSON.parse(fs.readFileSync(fn));

for (var attrname in local_settings) {
    exports[attrname] = local_settings[attrname];
}

console.log();
console.log("load settings.js.....");
console.log(exports);
console.log();
var http = require('http');
var fs   = require('fs');
var pdf  = require('html-pdf');
var port = 8124;

http.createServer(html2pdf).listen(port);
console.log('Server running at http://127.0.0.1:' + port + '/');

function html2pdf(req, res) {

    console.log(req.url);

    var html    = fs.readFileSync('./ticketTemplate.html', 'utf8');
    var options = {format: 'A4'};

    pdf.create(html, options).toFile('./ticket.pdf', function (err, result) {
        if (err) return console.log(err);

        console.log(result);

        var msg = "<!DOCTYPE html/><html><head><title>门票地址</title></head><body><a href='" + result.filename + "'/>" + result.filename + "</a></body></html>";

        res.end(msg);
    });

}
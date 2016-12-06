/**
 * Created by zhaohongyu on 2016/12/6.
 */
'use strict';

var r = require('rethinkdb')
    , settings = require("./conf/settings");

var connParam = {host: settings.rethinkdbHost, port: settings.rethinkdbPort};
function myconnect(err, conn) {
    if (err) throw err;
    // tableCre(conn);
    // add(conn);
    // getAll(conn);
    // getByName(conn, "Laura Roslin");
    // getByPk(conn, "9453f784-251c-4810-ac00-729315f7cdf6");
    update(conn);
}
r.connect(connParam, myconnect);

// 更新
function update(connection) {
    r.table('authors').update({type: "fictional"}).run(connection, function (err, result) {
        if (err) throw err;
        console.log(JSON.stringify(result, null, 2));
    });


    r.table('authors').filter(r.row("name").eq("William Adama")).update({rank: "Admiral"}).run(connection, function (err, result) {
        if (err) throw err;
        console.log(JSON.stringify(result, null, 2));
    });

    r.table('authors').filter(r.row("name").eq("Jean-Luc Picard")).update({
        posts: r.row("posts").append({
            title: "Shakespeare",
            content: "What a piece of work is man..."
        })
    }).run(connection, function (err, result) {
        if (err) throw err;
        console.log(JSON.stringify(result, null, 2));
    });
    
}

// 根据主键查询
function getByPk(connection, pk) {
    r.table('authors').get(pk).run(connection, function (err, result) {
        if (err) throw err;
        console.log(JSON.stringify(result, null, 2));
    });
}

// 根据条件查询
function getByName(connection, name) {

    r.table('authors').filter(r.row('name').eq(name)).run(connection, function (err, cursor) {
        if (err) throw err;
        cursor.toArray(function (err, result) {
            if (err) throw err;
            console.log(JSON.stringify(result, null, 2));
        });
    });
}

// query
function getAll(connection) {

    r.table('authors').run(connection, function (err, cursor) {
        if (err) throw err;
        cursor.toArray(function (err, result) {
            if (err) throw err;
            console.log(JSON.stringify(result, null, 2));
        });
    });

}

// add
function add(connection) {

    r.table('authors').insert([
        {
            name: "William Adama", tv_show: "Battlestar Galactica",
            posts: [
                {title: "Decommissioning speech", content: "The Cylon War is long over..."},
                {title: "We are at war", content: "Moments ago, this ship received word..."},
                {title: "The new Earth", content: "The discoveries of the past few days..."}
            ]
        },
        {
            name: "Laura Roslin", tv_show: "Battlestar Galactica",
            posts: [
                {title: "The oath of office", content: "I, Laura Roslin, ..."},
                {title: "They look like us", content: "The Cylons have the ability..."}
            ]
        },
        {
            name: "Jean-Luc Picard", tv_show: "Star Trek TNG",
            posts: [
                {title: "Civil rights", content: "There are some words I've known since..."}
            ]
        }
    ]).run(connection, function (err, result) {
        if (err) throw err;
        console.log(JSON.stringify(result, null, 2));
    })
}

// create table
function tableCre(connection) {
    // Create a new table
    r.db('test').tableCreate('authors').run(connection, function (err, result) {
        if (err) throw err;
        console.log(JSON.stringify(result, null, 2));
    });
}
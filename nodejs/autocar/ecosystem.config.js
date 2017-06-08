module.exports = {
    /**
     * Application configuration section
     * http://pm2.keymetrics.io/docs/usage/application-declaration/
     */
    apps: [
        {
            name        : "autocar",
            script      : "./cron.js",
            cwd         : "./",// 当前工作路径
            watch       : true,
            ignore_watch: [// 从监控目录中排除
                "\.git",
                "\.idea",
                "node_modules",
                "public",
            ],
        },
    ],
};

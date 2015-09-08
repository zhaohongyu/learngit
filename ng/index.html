<!DOCTYPE html>
<html ng-app="todoApp">
    <head lang="en">
        <meta charset="UTF-8">
        <title></title>
        <style>
            .done-true {
                text-decoration: line-through;
                color: grey;
            }
        </style>
    </head>
    <body>
        <h2>Todo</h2>
        <div ng-controller="TodoController">
            <span>{{remaining()}} of {{todos.length}} remaining</span>
            [ <a href="" ng-click="archive()">archive</a> ]
            <ul class="unstyled">
                <li ng-repeat="todo in todos">
                    <input type="checkbox" ng-model="todo.done">
                    <span class="done-{{todo.done}}">{{todo.text}}</span>
                </li>
            </ul>
            <form ng-submit="addTodo()">
                <input type="text" ng-model="todoText"  size="30"
                       placeholder="add new todo here">
                <input class="btn-primary" type="submit" value="add">
            </form>
        </div>
    <script src="http://ngnice.com/lib/angular/1.2.16/angular.js"></script>
    <script>
        angular.module('todoApp', [])
                .controller('TodoController', ['$scope', function($scope) {
                    $scope.todos = [
                        {text:'learn angular', done:true},
                        {text:'build an angular app', done:false}];

                    $scope.addTodo = function() {
                        $scope.todos.push({text:$scope.todoText, done:false});
                        $scope.todoText = '';
                    };

                    $scope.remaining = function() {
                        var count = 0;
                        angular.forEach($scope.todos, function(todo) {
                            count += todo.done ? 0 : 1;
                        });
                        return count;
                    };

                    $scope.archive = function() {
                        var oldTodos = $scope.todos;
                        $scope.todos = [];
                        angular.forEach(oldTodos, function(todo) {
                            if (!todo.done) $scope.todos.push(todo);
                        });
                    };
                }]);
    </script>
    </body>
</html>
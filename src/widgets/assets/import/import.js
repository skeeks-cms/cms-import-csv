/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 31.08.2016
 */
(function(sx, $, _)
{
    sx.createNamespace('classes.csv', sx);

    sx.classes.csv.ImportProgressBar = sx.classes.tasks.ProgressBar.extend({

        _init: function()
        {
            var self = this;
            this.applyParentMethod(sx.classes.tasks.ProgressBar, '_init', []);

            this.bind('update', function(e, data)
            {
                $(".sx-executing-task-name", self.getWrapper()).empty().append(data.Task.get('name'));
                $(".sx-executing-ptc", self.getWrapper()).empty().append(self.getExecutedPtc());
            });
        }
    });


    sx.classes.csv.Import = sx.classes.Component.extend({

        _init: function()
        {
            this.TaskManager = new sx.classes.tasks.Manager({
                'tasks' : [],
                'delayQueque' : this.get('delayQueque', 200)
            });

            this.ProgressBar = new sx.classes.csv.ImportProgressBar(this.TaskManager, "#sx-progress-tasks");

            /**
             * Данные задачи
             */
            this.Task = new sx.classes.Entity();
        },

        _onDomReady: function()
        {
            var self = this;
            this.jWidget = $("#" + this.get('id'));
            this.jBtnStart = $(".sx-start-btn", this.jWidget);
            this.jResultWrapper = $(".sx-result-wrapper", this.jWidget);
            this.jResultTable = $("table", this.jResultWrapper);
            this.jResultTableTbody = $("tbody", this.jResultTable);

            this.jBtnStart.bind('click', function()
            {
                self.run();
                return false;
            });
        },

        /**
         * @param countRows
         * @returns {sx.classes.csv.Import}
         */
        _loadTasks: function()
        {
            var self = this;

            var start           = self.Task.get('start');
            var end             = self.Task.get('end');
            var stepRange       = self.Task.get('step');

            var steps           = self.Task.get('totalSteps');

            var tasks = [];
            var from    = start;

            for (var step = 0; step < steps; step ++)
            {
                if (step > 0)
                {
                    from = from + stepRange;
                }


                if (from > end)
                {
                    from = end;
                }

                var tmpEnd = from + stepRange - 1;
                if (tmpEnd > end)
                {
                    tmpEnd = end;
                }

                var ajaxQuery = sx.ajax.preparePostQuery(this.get('backendStep'), {
                    'start' : from,
                    'end'   : tmpEnd,
                    'task'  : $('#' + self.get('formId')).serialize(),
                });

                new sx.classes.AjaxHandlerNoLoader(ajaxQuery);

                ajaxQuery.onSuccess(function(e, data)
                {
                    self._addResult(data.response.data);
                });

                var Task = new sx.classes.tasks.AjaxTask(ajaxQuery, {
                    'name': 'Строка: ' + from + ' - ' + (tmpEnd)
                });

                tasks.push(Task);
            }

            this.TaskManager.setTasks(tasks);
            return this;
        },

        /**
         * @returns {sx.classes.csv.Import}
         */
        run: function()
        {
            var self = this;

            this.trigger('run');
            this.jResultTableTbody.empty();

            var AjaxQuery = sx.ajax.preparePostQuery(this.get('backendLoadTask'), $('#' + this.get('formId')).serialize() );

            var AjaxHandler = new sx.classes.AjaxHandlerStandartRespose(AjaxQuery, {
                'allowResponseSuccessMessage' : false
            });

            AjaxHandler.bind('success', function(e, response)
            {
                self.Task.merge(response.data);
                self._loadTasks();
                self.TaskManager.start();
            });

            AjaxHandler.bind('error', function(e, response)
            {
                console.log('Ошибка');
                console.log(e);
                console.log(response.data);
            });

            AjaxQuery.execute();

            //
            return this;
        },

        _addResult: function(data)
        {
            var self = this;

            _.each(data.rows, function(info, number)
            {
                console.log(info);

                var jTr = $("<tr>")
                    .append($("<td>").append(number))
                    .append($("<td>").append(info.message))
                    .append($("<td>").append(info.html))
                ;

                if (info.success)
                {
                    jTr.addClass('sx-success');
                } else
                {
                    jTr.addClass('sx-error');
                }

                self.jResultTableTbody.append(jTr);
            });
        },

    });

})(sx, sx.$, sx._);
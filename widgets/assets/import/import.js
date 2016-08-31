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
            this._initTaskManager();
        },

        _onDomReady: function()
        {
            var self = this;
            this.jWidget = $("#" + this.get('id'));
            this.jBtnStart = $(".sx-start-btn", this.jWidget);

            this.jBtnStart.bind('click', function()
            {
                self.initTasks().start();
                return false;
            });
        },

        _initTaskManager: function()
        {
            this.TaskManager = new sx.classes.tasks.Manager({
                'tasks' : [],
                'delayQueque' : 200
            });

            this.ProgressBar = new sx.classes.csv.ImportProgressBar(this.TaskManager, "#sx-progress-tasks");
        },

        /**
         * @param countRows
         * @returns {sx.classes.csv.Import}
         */
        initTasks: function()
        {
            var stepRange    = 20;
            var steps = this.get('task').totalRows / stepRange;
            steps = steps.toFixed();
            steps = Number(steps) + 1;

            var tasks = [];

            var from    = 0;

            for (var step = 0; step < steps; step ++)
            {
                from = stepRange * step;

                var ajaxQuery = sx.ajax.preparePostQuery(this.get('backend'), {
                    'rowStart': from,
                    'rowEnd': from + stepRange,
                    'importfilepath': $('#importstocksalemodel-importfilepath').val()
                });

                new sx.classes.AjaxHandlerNoLoader(ajaxQuery);

                var Task = new sx.classes.tasks.AjaxTask(ajaxQuery, {
                    'name': 'Строка: ' + from + ' - ' + (from + stepRange)
                });

                tasks.push(Task);
            }

            this.TaskManager.setTasks(tasks);
            return this;
        },

        /**
         * @returns {sx.classes.csv.Import}
         */
        start: function()
        {
            this.TaskManager.start();
            return this;
        }

    });

})(sx, sx.$, sx._);
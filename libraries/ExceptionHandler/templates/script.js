var ExceptionTrace = new Class({
    initialize: function(traceClass, currentClass) {
        this.currentId      = null;
        this.traceClass     = traceClass;
        this.currentClass   = currentClass;

        this._hideAll();
        this._showFirst();
    },

    show: function(id) {
        var self = this;

        if (self.currentId == id) {
            self._hideCurrent();
        } else {
            self.currentId = id;

            var trace   = $('trace_' + id);
            var current = $('fileInfo_' + id);

            new Fx.Slide(trace).slideIn();
            self._slideOutAll();
            current.addClass(self.currentClass);
        }
    },

    _showFirst: function() {
        var self = this;

        var firstTrace  = $$('div.first')[0];
        var id          = firstTrace.get('id').split('_')[1];
        var current     = $('fileInfo_' + id);

        new Fx.Slide(firstTrace).show();
        current.addClass(self.currentClass);

        self.currentId = id;
    },

    /**
     * Hides current opened trace
     */
    _hideCurrent: function() {
        var self = this;

        var trace   = $('trace_' + self.currentId);
        var current = $('fileInfo_' + self.currentId);

        current.removeClass(self.currentClass);
        new Fx.Slide(trace).slideOut();

        self.currentId = null;
    },

    /**
     * Slides out all the traces except the current
     */
    _slideOutAll: function() {
        var self = this;

        $$(self.traceClass).each(function(el) {
            var id      = el.get('id').split('_')[1];
            var current = $('fileInfo_' + id);

            current.removeClass(self.currentClass);
            new Fx.Slide(el).slideOut();
        });
    },

    /**
     * Hides all the traces
     */
    _hideAll: function() {
        var self = this;

        $$(self.traceClass).each(function(el) {
            new Fx.Slide(el).hide();
        });
    },
});

var currentClass    = 'current';
var exceptionTrace  = new ExceptionTrace('.trace', currentClass);

window.addEvent('domready', function() {
    $$('.fileInfo').each(function(el) {
        var id = el.get('id').split('_')[1];

        el.setStyle('cursor', 'pointer');

        // Add events
        el.addEvents({
            mouseover: function() {
                this.addClass('underline');
            },
            mouseleave: function() {
                this.removeClass('underline');
            },
            click: function() {
                exceptionTrace.show(id)
            }
        });
    });
});
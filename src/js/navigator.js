
Jaram.Navigator = Class.create();
Jaram.Navigator.prototype = {
	initialize: function(element) {
		this.element   = $(element);
		this.active    = false; 
		Event.observe(this.element, 'keyup', this.navListener.bindAsEventListener(this));
		this.active    = true;
	},

	navListener: function(event) {
		if(this.active) {
//			alert(event.keyCode);
			switch(event.keyCode) {
				case 188:
					if (previous_link.length > 0)
					{
						location.href = previous_link;
						Event.stop(event);	
					}
					
					return;
				case 190:
					if (next_link.length > 0)
					{
						location.href = next_link;
						Event.stop(event);	
					}
					return;
			}
		}
	},
	stopNav : function() {
		this.active = false;
	},
	resumeNav : function() {
		this.active = true;
	}
};

new Jaram.Navigator(document);
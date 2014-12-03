$(document).ready(function() {
	function misModel() {
		var self = this; 
		
		self.loadCategories = function() {
			return $.ajax({
    	  url: 'http://localhost/finance/app_getCategories.php',
    		type: 'get'
    	}).promise();
		};

		self.


	};

	ko.applyBindings(new misModel());
});
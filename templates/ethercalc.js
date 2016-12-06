il.Ethercalc = (function (scope) {
	'use strict';

	var pub = {}, pro = {};

	pub.config = {
		id				: '#ilEtherCalcPlugin',
		dimension_class	: '.ilTabsContentOuter'
	};

	pub.setDimensions = function()
	{
		if($(pub.config.id).length > 0)
		{
			var dim_class = $(pub.config.dimension_class);
			$(pub.config.id).height($(window).height()).width(dim_class.width());
		}
	};

	pub.protect = pro;
	return pub;

}(il));

$(document).ready(function(){
	il.Ethercalc.setDimensions();
});
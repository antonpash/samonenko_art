<?php

?>
  <script>  
  var $ = jQuery.noConflict();
  </script>  
	<script src="<?php echo SAPHALI_PLUGIN_ZAKUPKA_URL; ?>libraries/RGraph.common.core.js" ></script>
    <script src="<?php echo SAPHALI_PLUGIN_ZAKUPKA_URL; ?>libraries/RGraph.common.effects.js" ></script>
    <script src="<?php echo SAPHALI_PLUGIN_ZAKUPKA_URL; ?>libraries/RGraph.common.tooltips.js" ></script>
    <script src="<?php echo SAPHALI_PLUGIN_ZAKUPKA_URL; ?>libraries/RGraph.line.js" ></script>
    
    <script src="<?php echo SAPHALI_PLUGIN_ZAKUPKA_URL; ?>/libraries/RGraph.common.dynamic.js" ></script>

<style>
    .RGraph_tooltip {
        background-color: white ! important;
    }
</style>
<script>
	var data = [<?php echo implode( ', ', (array)$day_dohod );?>];
	var dataMonth = ['', 'Январь', 'Февраль', 'Март', 'Апрель', 
						 'Май', 'Июнь', 'Июль', 'Август',
						 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
					 ];
      function myTooltipFunc (idx)
        {
            var tooltips = myFormatter(0, data[idx]);
            return tooltips;
        }
  jQuery(document).ready(function ()
        {
		jQuery(".step_day").blur(function(){
			if( jQuery(this).val() == 0 ) {
				jQuery(".action_net_zakupka button").text('текущая неделя');
			} else if( jQuery(this).val() == 1 ) {
				jQuery(".action_net_zakupka button").text('неделей ранее');
			}else  {
				jQuery(".action_net_zakupka button").text('неделями ранее');
			}
		});
		jQuery(".step_day").click(function(){
			if( jQuery(this).val() == 0 ) {
				jQuery(".action_net_zakupka button").text('текущая неделя');
			} else if( jQuery(this).val() == 1 ) {
				jQuery(".action_net_zakupka button").text('неделей ранее');
			}else  {
				jQuery(".action_net_zakupka button").text('неделями ранее');
			}
		});
		function myCallback(response) {
						RGraph.Reset(document.getElementById("cvs2"));
						data = response.d;
						var line = new RGraph.Line({
						id: 'cvs2',
						data: response.d,
						options: {
							spline: false,
							numxticks: 11,
							numyticks: 5,
							background: {
								color: 'white',
								grid: {
									autofit: {
										numvlines: 11,    
										vlines: false,
										border: false
									}
								}
							},
							colors: ['Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
							linewidth: 1,
							gutter: {
								left: 70,
								right: 25,
								top: 60,
								bottom: 40
							},
							labels: response.day,
							shadow: {
								color: '#aaa',
								blur: 5
							},
							tickmarks: 'circle',
							title : {
								self: 'Чистый доход в магазине (посуточно)',
								vpos: 0.5,
								xaxis: {
									self: dataMonth[response.m],
									pos: 0.2
								}
							},//'Чистый доход в магазине (посуточно)',
							}
						}).set('tooltips', RGraph.ISOLD ? null : [myTooltipFunc, myTooltipFunc, myTooltipFunc, myTooltipFunc, myTooltipFunc, myTooltipFunc])
						.set('labels.above', true)
						.set('scale.formatter', myFormatter)
						.set('hmargin', 10)
						.set('tooltips.effect', 'fade').draw() ;
		}
		jQuery('.action_net_zakupka').on('click', 'button', function() {
			RGraph.clear(document.getElementById("cvs2"));
			RGraph.AJAX.getJSON(ajaxurl+'?action=zakupka_next_diapazon&step_day='+(parseInt(jQuery(".step_day").val(), 10) + 1), myCallback);
		});

	var i=data.length;
	var label = Array();
	for(i; i>0; i--) {
		label[data.length - i] =  new Date( new Date().getTime() - (i-1)*24*3600*1000 ).getDate() + '';
	}


				

	label1 = label;
	//line.set('tooltips', ['John', 'Fred', 'Lou']);
     var line = new RGraph.Line({
        id: 'cvs2',
        data: data,
        options: {
            spline: false,
            numxticks: 11,
            numyticks: 5,
            background: {
                grid: {
                    autofit: {
                        numvlines: 11
                    }
                }
            },
            colors: ['Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
            linewidth: 1,
            gutter: {
                left: 70,
                right: 25,
                top: 60,
                bottom: 40
            },
            labels: label,
            key: { background: '#ccc'},
            shadow: {
                color: '#aaa',
                blur: 5
            },
            tickmarks: 'circle',
			title : {
				self: 'Чистый доход в магазине (посуточно)',
				vpos: 0.5,
				xaxis: {
				self: dataMonth[<?php echo date('n',strtotime( '- ' . 3 . ' Day', date( time() ) )); ?>],
				pos: 0.2,
				}
			},//'Чистый доход в магазине (посуточно)',
        }
    }).set('tooltips', RGraph.ISOLD ? null : [myTooltipFunc, myTooltipFunc, myTooltipFunc, myTooltipFunc, myTooltipFunc, myTooltipFunc])
                .set('labels.above', true)
                .set('scale.formatter', myFormatter)
                .set('hmargin', 10)
				
                .set('tooltips.effect', 'fade').draw() ;
        });
		
	function myFormatter(obj, num)
	{
		return num + "<?php  echo html_entity_decode('&nbsp;', ENT_NOQUOTES, 'UTF-8') . str_replace('"', '\\"', html_entity_decode( get_woocommerce_currency_symbol(), ENT_NOQUOTES, 'UTF-8' ) );?>"; // An example of formatting
	}

</script>
<canvas id="cvs2" width="700" height="400">[No canvas support]</canvas> <div class='action_net_zakupka'><input style="padding: 5px;" class="step_day" type="number" step="1" min="0" value="1" /> <button>неделей ранее</button></div>
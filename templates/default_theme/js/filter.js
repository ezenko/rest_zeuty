
    $(document).ready(function(){
        
        
        
        $('#rest_country').linkselect( {
            change: function(li, value, text){
                $.get('/location2.php?sec=ip&sel=region&id_country=' + value,
                function(data){
                    $("#rest_region").linkselect("replaceOptions", data, false);
                },
                "json"
                );
            }
        }
        );
        $('#rest_region').linkselect( {
            change: function(li, value, text){
                $.get('/location2.php?sec=ip&sel=city&id_region=' + value,
                function(data){
                    $("#rest_city").linkselect("replaceOptions", data, false);
                },
                "json"
                );
            }
        }
        );
        
        $('#rest_city').linkselect();
        $('#rest_realty').linkselect();
        
        $('#rest_submit').click(function(){
            $form = $('#filterForm');
            var country = parseInt($('#rest_country').linkselect("val"));
            var region = parseInt($('#rest_region').linkselect("val"));
            var city = parseInt($('#rest_city').linkselect("val"));
            var realty = parseInt($('#rest_realty').linkselect("val"));
            
            if(!isNaN(country)) {
                $form.find('input[name=country]').val(country);
            }
            if(!isNaN(region)) {
                $form.find('input[name=region]').val(region);
            }
            if(!isNaN(city)) {
                $form.find('input[name=city]').val(city);
            }
            if(!isNaN(realty)) {
                $form.find('input[name^=realty_type]').val(realty);
            }
            $form.find('input[name=id]').val($('#rest_id').val());
            $form.find('input[name=choise]').val(1);
            $form.submit();
        });
        
        $('#active_country').linkselect( {
            change: function(li, value, text){
                $.get('/location2.php?sec=ip&sel=region&id_country=' + value,
                function(data){
                    $("#active_region").linkselect("replaceOptions", data, false);
                },
                "json"
                );
            }
        }
        );
        
        $('#active_region').linkselect();
        $('#active_theme').linkselect();
        
        $('#active_submit').click(function(){
            $form = $('#filterForm');
            var country = parseInt($('#active_country').linkselect("val"));
            var region = parseInt($('#active_region').linkselect("val"));
            var theme = parseInt($('#active_theme').linkselect("val"));
            
            if(!isNaN(country)) {
                $form.find('input[name=country]').val(country);
            }
            if(!isNaN(region)) {
                $form.find('input[name=region]').val(region);
            }
            if(!isNaN(theme)) {
                $form.find('input[name^=theme_rest]').val(theme);
            }
            $form.find('input[name=id]').val($('#active_id').val());
            $form.find('input[name=choise]').val(4);
            $form.find('input[name=sel]').val('category');
            $form.submit();
        });
        
        $('#tours_from').linkselect();
        $("#tours_city").linkselect();
        $('#tours_country').linkselect({
            change: function(li, value, text){
                $.get('/location2.php?sec=ip&sel=tours_city&id_country=' + value,
                function(data){
                    $("#tours_city").linkselect("replaceOptions", data, false);
                },
                "json"
                );
            }
        });
    });

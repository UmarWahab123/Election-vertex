import AppForm from '../app-components/Form/AppForm';

Vue.component('polling-station-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                polling_station_number:  '' ,
                meta:  '' ,
                url_id:  '' ,
                
            }
        }
    }

});
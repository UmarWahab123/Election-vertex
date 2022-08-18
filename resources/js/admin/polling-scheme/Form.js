import AppForm from '../app-components/Form/AppForm';

Vue.component('polling-scheme-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                ward:  '' ,
                polling_station_area:  '' ,
                block_code_area:  '' ,
                block_code:  '' ,
                latlng:  '' ,
                status:  '' ,
                
            }
        }
    }

});
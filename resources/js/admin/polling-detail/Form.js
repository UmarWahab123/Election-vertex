import AppForm from '../app-components/Form/AppForm';

Vue.component('polling-detail-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                polling_station_id:  '' ,
                serial_no:  '' ,
                family_no:  '' ,
                gender:  '' ,
                polling_station_number:  '' ,
                cnic:  '' ,
                page_no:  '' ,
                url:  '' ,
                url_id:  '' ,
                boundingBox:  '' ,
                polygon:  '' ,
                status:  '' ,

            }
        }
    }

});

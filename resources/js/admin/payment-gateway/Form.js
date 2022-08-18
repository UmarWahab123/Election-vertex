import AppForm from '../app-components/Form/AppForm';

Vue.component('payment-gateway-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                business_id:  '' ,
                ref_id:  '' ,
                service_charges:  '' ,
                expiry_date:  '' ,
                on_demand_cloud_computing:  false ,
                multi_bit_visual_redux:  false ,
                scan_reading:  false ,
                googly:  false ,
                img_url:  '' ,
                status:  false ,
                meta:  '' ,

            }
        }
    }

});

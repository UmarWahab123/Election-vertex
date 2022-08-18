import AppListing from '../app-components/Listing/AppListing';

Vue.component('payment-gateway-listing', {
    mixins: [AppListing],
    props: {
        'activation': {
            type: Boolean,
            required: true
        },
    }
});

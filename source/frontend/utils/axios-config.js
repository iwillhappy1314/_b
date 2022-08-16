/**
 * As we are using hash based navigation, hack fix
 * to highlight the current selected menu
 *
 * Requires jQuery
 */
import Qs from 'qs';

const axiosConfig = {
    transformRequest: [
        function(data) {
            return Qs.stringify(data);
        }],
    headers         : {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-WP-Nonce'  : wenpriseSpaceNameFrontendSettings.nonce,
    },
};

export default axiosConfig;
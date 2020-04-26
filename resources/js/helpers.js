export default {

    // Prepare params for you that will be use on the request
    prepareParams(options, search = '') {
        const { sortBy, sortDesc, page, itemsPerPage } = options
        return {
            page: page,
            limit: itemsPerPage,
            sortBy: sortBy ? sortBy[0] : null,
            sortDesc: sortDesc ? sortDesc[0] : null,
            search: search,
        }
    },

    // Handle errors and return all errors in a string
    getErrors(error) {
        let errors = ['There seems to be an error!'];
        let errResponse = error.response;
        console.log('[ERROR] response', errResponse);

        // check for unauthenticed request, redirect to login
        if (
            errResponse.data &&
            errResponse.data.status_code == 500 &&
            errResponse.data.message == "Unauthenticated."
        ) {
            localStorage.removeItem('jwt');
            window.location.href = '/';
            return 'Need to login again.';
        }


        // check if error has response and data errors
        if (errResponse && errResponse.data && errResponse.data.errors) {
            errors = [];
            let errItems = errResponse.data.errors;
            // iterate error items
            for (const index in errItems ) {
                // checks if an item has more errors
                if (typeof errItems[index] === 'Array') {
                    errors.push(errItems[index].join(', '));
                } else {
                    // otherwise just push
                    errors.push(errItems[index]);
                }
            }
        } else if (errResponse.data.status_code === 500 && errResponse.data.message) {
            errors.push(errResponse.data.message);
        } else {
            console.log('[ERROR] is : ', error)
        }

        return errors.join(', ');
    },

    // This helper function will change the url without refreshing page
    pushState(url, params) {
        let newParams = Object.keys(params)
                            .map(key => {
                              return (encodeURIComponent(key) + '=' + encodeURIComponent(params[key]))
                            })
        let newUrl = url + '?' + newParams.join('&')

        history.pushState(params, null, newUrl)
    },


    // This helper function will return specific color of specific status
    getColor(status) {
        if (!status) return 'gray'

        let color = ''
        switch(status.toLowerCase()) {
            case 'pending_machship':
            case 'pending':
                color = 'warning lighten-1'
                break
            case 'process':
            case 'progress':
                color = 'info darken-2'
                break
            case 'machship_error':
            case 'fail':
            case 'error':
                color = 'error'
                break;
            case 'active':
            case 'success':
            case 'completed':
                color = 'success'
                break;
            default:
                color = 'gray'
        }


        return color
    },

    // this is where we set pagination options
    getPaginateOptions() {
        return {
            page: 1,
            itemsPerPage: 10,
            sortBy: [],
            sortDesc: [],
            groupBy: [],
            groupDesc: [],
            mustSort: false,
            multiSort: false,
        }
    }
}
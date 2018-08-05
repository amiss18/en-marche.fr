import callApi from '../utils/api';

// Action types
export const GET_HEADER = 'GET_HEADER';
export const GET_FOOTER = 'GET_FOOTER';

export function getHeaderFragment() {
    return {
        type: GET_HEADER,
        payload: callApi('/api/dom'),
    };
}

export function getFooterFragment() {
    return {
        type: GET_FOOTER,
        payload: callApi('api/dom'),
    };
}

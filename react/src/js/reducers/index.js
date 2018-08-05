import { combineReducers } from 'redux';
import filter from './filter';
import stats from './stats';
import user from './user';
import domFragment from './domFragment';
import { routerReducer } from 'react-router-redux';

export default combineReducers({
    filter,
    stats,
    user,
    routing: routerReducer,
    domFragment,
});

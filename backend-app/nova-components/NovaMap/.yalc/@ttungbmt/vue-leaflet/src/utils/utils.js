import {Map} from 'leaflet'

export const findLeafletParent = firstVueParent => {
    let found = false;
    while (firstVueParent && !found) {
        if (firstVueParent.mapObject === undefined || !(firstVueParent.mapObject instanceof  Map)) {
            firstVueParent = firstVueParent.$parent;
        } else {
            found = true;
        }
    }
    return firstVueParent;
}
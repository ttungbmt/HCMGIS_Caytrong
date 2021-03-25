import {nanoid} from "nanoid";

export const formatLayers = (layers = [], boundary) => {
    return layers ? layers.map(layer => {
        if (layer.boundary && boundary) {
            delete layer['boundary']
            layer.options.boundary = boundary
        }

        return {id: nanoid(), ...layer}
    }) : []
}

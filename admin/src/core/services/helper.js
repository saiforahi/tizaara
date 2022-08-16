export const deepCopy = obj => {
    if (!obj)
        return obj;

    let v, newObj = Array.isArray(obj) ? [] : {};
    for (const k in obj) {
        v = obj[k];
        newObj[k] = (typeof v === "object") ? deepCopy(v) : v;
    }

    return newObj;
}

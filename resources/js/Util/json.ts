export const lowerize = obj =>
  Object.keys(obj).reduce((acc, k) => {
    acc[k.toLowerCase()] = obj[k];
    return acc;
  }, {});

export const lowerizeArray = (arr:Array<any>) => {
    return arr.map(o => lowerize(o))
}


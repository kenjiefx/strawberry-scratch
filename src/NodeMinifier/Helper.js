
const fh=(fn)=>{
    return fn.substring(0,fn.indexOf('{') - 1)
}

const fb=(fn)=>{
    return fn.substring (fn.indexOf("{") + 1,fn.lastIndexOf("}"))
}

const fa=(fn)=>{
    const handlerStr = fn.split('{')[0]
    const matchedFn  = handlerStr.match(/(?<=\().+?(?=\))/g)
    if (matchedFn===null || /[(={})]/g.test(matchedFn[0])) {
        // try if it's a single param function 
        if (handlerStr.includes('=>')&&!handlerStr.includes('(')&&!handlerStr.includes(')')) {
            return [handlerStr.split('=>')[0].trim()]
        }
        return []
    }
    return matchedFn[0].split(',').map(item=>item.trim())
}

const registry = {}
const strawberry = {
    create:(name)=>{
        return {
            factory:(name,callback)=>{
                registry[name]={
                    type:'factory',
                    head: fh(callback.toString()),
                    body: fb(callback.toString()),
                    arguments: fa(callback.toString())
                }
            },
            service:(name,callback)=>{
                registry[name]={
                    type:'service',
                    head: fh(callback.toString()),
                    body: fb(callback.toString()),
                    arguments: fa(callback.toString())
                }
            },
            component:(name,callback)=>{
                registry[name]={
                    type:'component',
                    head: fh(callback.toString()),
                    body: fb(callback.toString()),
                    arguments: fa(callback.toString())
                }
            },
        }
    }
};

export default class ScriptLoader {
    public static loadFrom(document: Document)
    {
        let scripts: string[] = [];
        window.document.querySelectorAll('script[src]').forEach((script: HTMLScriptElement) => {
            scripts.push(script.src)
        })

        document.querySelectorAll('script[src]').forEach((script: HTMLScriptElement) => {
            if (!scripts.includes(script.src)) {
                const newScript = document.createElement('script');
                newScript.src = script.src;
                window.document.head.append(newScript);
            }
        })
    }
}
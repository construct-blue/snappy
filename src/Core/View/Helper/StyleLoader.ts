export default class StyleLoader
{
    public static loadFrom(document: Document)
    {
        let styles: string[] = [];
        window.document.querySelectorAll('link[rel=stylesheet][href]').forEach((style: HTMLLinkElement) => {
            styles.push(style.href)
        })

        document.querySelectorAll('link[rel=stylesheet][href]').forEach((style: HTMLLinkElement) => {
            if (!styles.includes(style.href)) {
                window.document.head.append(style.href);
            }
        })
    }
}
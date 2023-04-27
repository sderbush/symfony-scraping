(() => {
    const getElementsByXpath = function (xpathToExecute, contextNode = null) {
        const result = [];
        const nodesSnapshot = document.evaluate(xpathToExecute, contextNode || document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
        for (let i = 0; i < nodesSnapshot.snapshotLength; i++) {
            result.push(nodesSnapshot.snapshotItem(i));
        }
        return result;
    };
    const getElementByXpath = function (path, contextNode = null) {
        return document.evaluate(path, contextNode || document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
    }
    const parseCardMovie = function (el) {
        const positionEl = getElementByXpath('.//*[contains(@class, "styles_position_")]', el);
        if (!positionEl) {
            return null
        }
        const rating = getElementByXpath('.//*[contains(@class, "styles_kinopoiskValueBlock_")]', el);
        const title = getElementByXpath('.//*[contains(@class, "styles_mainTitle_")]', el);
        const origin = getElementByXpath('.//*[contains(@class, "desktop-list-main-info_secondaryTitle_")]', el);
        const year = getElementByXpath('.//*[contains(@class, "desktop-list-main-info_secondaryText_")]', el);
        const count = getElementByXpath('.//*[contains(@class, "styles_kinopoiskCount_")]', el);

        return {
            position: Number(positionEl.textContent),
            rating: rating ? Number(rating.textContent.replace(',', '.')) : null,
            title: origin ? origin.textContent : (title ? title.textContent : null),
            year: year ? Number(year.textContent.replace(/^,/, '').split(',')[0]) : null,
            count: count ? Number(count.textContent.replace(' ', '')) : null,
        }
    }

    const movies = getElementsByXpath('//*[@id="selections_content_start_anchor"]/following-sibling::main/div')
        .map(parseCardMovie)
        .filter(Boolean)
        .slice(0, 10)

    return JSON.stringify(movies);
})()
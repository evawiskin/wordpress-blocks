function teamCardMouseover(parentId, slug) {
    const blockTeams = document.getElementById(parentId);
    if(!blockTeams) return;

    const cards = blockTeams.querySelectorAll(`[data-team]`);
    for(let index = 0; index < cards.length; index++) {
        if(cards[index].dataset.team == slug)
            cards[index].classList.remove("opacity-50");
        else
            cards[index].classList.add("opacity-50");
    }
}
function teamCardMouseleave(parentId) {
    const blockTeams = document.getElementById(parentId);
    if(!blockTeams) return;

    const cards = blockTeams.querySelectorAll(`[data-team]`);
    for(let index = 0; index < cards.length; index++) {
        cards[index].classList.remove("opacity-50");
    }
}
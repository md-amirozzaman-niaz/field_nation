
moveElement = (el,canvas,ranger=1000) => {

    let yPointer = canvas.innerHeight - 100,
        xPointer = canvas.innerWidth - 100,
        xPos = el.offsetLeft,
        yPos = el.offsetTop,
        yDir = el.dataset.yDirection,
        xDir = el.dataset.xDirection;

        // controll y direction
        // moving top -> bottom
        if(yDir == 0){
            el.style.top=yPos+10+'px';
            if(yPos+5 > yPointer){
                el.dataset.yDirection = 1;
            }
        }
        // moving bottom -> top
        if(yDir == 1){
            el.style.top=yPos-10+'px';
            if(yPos-5 < 0){
                el.dataset.yDirection = 0;
            }
        }

        // controll x direction

        // moving left -> right
        if(xDir == 0){
            el.style.left=xPos+10+'px';
            if(xPos+5 > xPointer){
                el.dataset.xDirection = 1;
            }
        }
        // moving left <- right
        if(xDir == 1){
            el.style.left=xPos-10+'px';
            if(xPos-5 < 0){
                el.dataset.xDirection = 0;
            }
        }
    // set transition
    el.style.transitionDuration = ranger < 200 ? (Number(ranger)+75)+'ms' : ranger+'ms';
}
let ranger= document.getElementById('ranger').value;
let mover= setInterval(
    ()=> {
        let el = document.getElementById('dot');
        let canvas = window;
        moveElement(el,canvas,ranger)
    },ranger
);
setNewSession = () => {
    let ranger= document.getElementById('ranger').value;
    document.getElementById('ranger-value').innerText=ranger;
    clearInterval(mover);
    mover = setInterval( () => {
            let el = document.getElementById('dot');
            let canvas = window;
            moveElement(el,canvas,ranger)
        },ranger)
}


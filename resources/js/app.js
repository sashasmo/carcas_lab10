import './bootstrap';
import 'admin-lte';    
window.Echo.channel('comment-count-channel')
        .listen('CommentCountChanged', (e) => {
            const el = document.getElementById('comment_count');
            if(el){
                el.textContent = "Comment count: "+ e.comment_count;
            }
        });
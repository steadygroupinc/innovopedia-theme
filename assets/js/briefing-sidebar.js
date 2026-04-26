/**
 * Innovopedia AI Briefing Sidebar JS
 */

jQuery(document).ready(function($) {
    let currentStoryIndex = 0;
    let stories = [];
    let isPlaying = false;
    let speechUtterance = null;
    let progressInterval = null;

    // Open Sidebar
    $(document).on('click', '.your-briefing-trigger', function(e) {
        e.preventDefault();
        $('#innovopedia-briefing-sidebar').addClass('active');
        if (stories.length === 0) {
            fetchBriefing();
        }
    });

    // Close Sidebar
    $('#close-briefing, #briefing-overlay').on('click', function() {
        $('#innovopedia-briefing-sidebar').removeClass('active');
        stopBriefing();
    });

    // Fetch Briefing via AJAX
    function fetchBriefing() {
        $('#briefing-loader').show();
        $.ajax({
            url: innovopediaBriefing.ajax_url,
            type: 'POST',
            data: {
                action: 'innovopedia_get_briefing',
                nonce: innovopediaBriefing.nonce
            },
            success: function(response) {
                $('#briefing-loader').hide();
                if (response.success) {
                    stories = response.data.stories;
                    renderStories();
                    setupProgressSegments();
                }
            }
        });
    }

    function renderStories() {
        const container = $('#briefing-stories-container');
        container.empty();
        stories.forEach((story, index) => {
            const storyHtml = `
                <div class="briefing-story" data-index="${index}">
                    <div class="story-meta">
                        <img src="${story.image}" class="story-image" alt="${story.title}">
                        <h3 class="story-title">${story.title}</h3>
                        <div class="story-summary">${story.summary}</div>
                    </div>
                    <a href="${story.link}" class="dive-deeper">Dive Deeper <i class="rbi rbi-arrow-right"></i></a>
                </div>
            `;
            container.append(storyHtml);
        });
        setActiveStory(0);
    }

    function setupProgressSegments() {
        const progressContainer = $('#briefing-progress-segments');
        progressContainer.empty();
        stories.forEach((_, index) => {
            progressContainer.append(`<div class="progress-segment" data-index="${index}"><div class="progress-fill"></div></div>`);
        });
    }

    function setActiveStory(index) {
        currentStoryIndex = index;
        $('.briefing-story').removeClass('active').css('opacity', '0.3');
        $(`.briefing-story[data-index="${index}"]`).addClass('active').css('opacity', '1');
        
        // Scroll to active story
        const container = $('#briefing-stories-container');
        const story = $(`.briefing-story[data-index="${index}"]`);
        container.animate({
            scrollTop: story.offset().top - container.offset().top + container.scrollTop() - 20
        }, 500);

        if (isPlaying) {
            readStory(index);
        }
    }

    function readStory(index) {
        window.speechSynthesis.cancel();
        const story = stories[index];
        const textToRead = `${story.title}. ${story.summary}`;
        
        speechUtterance = new SpeechSynthesisUtterance(textToRead);
        speechUtterance.rate = 1.0;
        speechUtterance.pitch = 1.0;

        speechUtterance.onstart = function() {
            startProgress(index);
        };

        speechUtterance.onend = function() {
            if (currentStoryIndex < stories.length - 1) {
                setActiveStory(currentStoryIndex + 1);
            } else {
                stopBriefing();
            }
        };

        window.speechSynthesis.speak(speechUtterance);
    }

    function startProgress(index) {
        clearInterval(progressInterval);
        const segment = $(`.progress-segment[data-index="${index}"] .progress-fill`);
        let progress = 0;
        const duration = 10000; // Estimated 10 seconds per story if Speech API doesn't give exact duration
        
        progressInterval = setInterval(() => {
            progress += 1;
            segment.css('width', progress + '%');
            if (progress >= 100) clearInterval(progressInterval);
        }, duration / 100);
    }

    function stopBriefing() {
        window.speechSynthesis.cancel();
        isPlaying = false;
        $('#play-pause-briefing i').removeClass('rbi-pause').addClass('rbi-play');
        clearInterval(progressInterval);
    }

    // Play/Pause Button
    $('#play-pause-briefing').on('click', function() {
        if (isPlaying) {
            stopBriefing();
        } else {
            isPlaying = true;
            $(this).find('i').removeClass('rbi-play').addClass('rbi-pause');
            readStory(currentStoryIndex);
        }
    });
});

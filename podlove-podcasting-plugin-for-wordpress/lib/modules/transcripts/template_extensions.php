<?php

namespace Podlove\Modules\Transcripts;

class TemplateExtensions
{
    /**
     * Transcript, grouped by speaker.
     *
     * **Examples**
     *
     * ```
     * <style type="text/css">
     * .ts-speaker { font-weight: bold; }
     * .ts-items { margin-left: 20px; }
     * .ts-time { font-size: small; color: #999; }
     * </style>
     * {% for group in episode.transcript %}
     *   <div class="ts-group">
     *     {% if group.contributor %}
     *       <div class="ts-speaker">{{ group.contributor.name }}</div>
     *     {% endif %}
     *     <div class="ts-items">
     *     {% for line in group.items %}
     *       <span class="ts-time">{{ line.start }}&ndash;{{ line.end }}</span>
     *       <div class="ts-content">{{ line.content }}</div>
     *     {% endfor %}
     *     </div>
     *   </div>
     * {% endfor %}
     * ```
     *
     * @accessor
     *
     * @dynamicAccessor episode.transcript
     *
     * @param mixed $return
     * @param mixed $method_name
     */
    public static function accessorEpisodeTranscript($return, $method_name, \Podlove\Model\Episode $episode)
    {
        return $episode->with_blog_scope(function () use ($episode) {
            $transcript = Model\Transcript::get_transcript($episode->id);
            $transcript = Model\Transcript::prepare_transcript($transcript, 'grouped');

            if (!is_array($transcript)) {
                return [];
            }

            return array_map(function ($group) {
                $lines = array_map(function ($line) {
                    return new Template\Line($line);
                }, $group['items']);

                return new Template\Group($lines, $group['speaker'] ?? null, $group['voice'] ?? null);
            }, $transcript);
        });
    }
}

<section class="storytelling">
    <div class="container">
        <div class="row">
            <div class=" marquee col-12">
                <p>“Art is the heritage of humanity on earth."</p>
                <p>The Earth Legacy team is shaking up the NFT community by launching a new concept: The NFT Cryptokart, the first artistic collaborative project.</p>
                <p>The original project is to create a collaborative artistic work, which will contain a part of the history of humanity, in the form of a rocket where each artist can contribute by creating their own level.</p>
                <p>The objective is twofold. Freezing part of the earth's legacy for future generations using the power of blockchain. But also to form a large community in which creativity, sharing and mutual aid will be the main values.</p>
                <p>
                    It is therefore around this innovative, delirious and completely offbeat concept that you will be able to integrate the community.<br />
                    - As an artist, your mission is to transmit through your contribution a part of culture or history, for example by referring to an event, a monument, pop culture, cinema, retro gaming, tv, etc.<br />
                    - As a full member, you become an ambassador of the project which contributes to its development and to the support of artistic creativity.
                </p>
                <p>Each work is associated with an NFT registered on the Ethereum blockchain. It is then sold at auction in order to promote the artist's work. Part of the earnings from each sale, as well as any resale in the secondary market, will go to you as an artist or community member.</p>
                <p>The NFT Cryptokart goes on a mission to create something incredible, will take you far beyond the moon, enter the Earth Legacy.</p>
            </div>
        </div>
    </div>
</section>

<div class="rocketBlocs">
    {% set nbNfts = 10000 %}
    {% set nftsViewed = -1 %}

    {% for nft in nfts %}
        {% set nftsViewed = nftsViewed + 1 %}
        {% set createLevel = false %}
        {% if nft.nft_tokenid is not null AND nft.nft_tokenid == '0' %}
            {% set createLevel = true %}
        {% endif %}
        <div class="rocketBloc" {% if nft.level_order %}id="level{{ nft.level_order }}"{% endif %} style="z-index:{{ nbNfts - nftsViewed }};">
            <div class="lazy rocketLevel {% if createLevel %}createLevel{% endif %}" data-wow-delay="800ms" data-wow-duration="3000ms" data-bg="{{ nft.filename }}">
                {%  if nft.nft_link is not empty %}
                    <a class="nft" href="{{ nft.nft_link }}" {% if createLevel is false %}target="_blank"{% endif %}></a>
                {%  endif %}
                <div class="meta">
                    <div>
                        {% if createLevel %}
                        <div class="create-btn">
                            <a href="{{ nft.nft_link }}"><i class="fas fa-pencil-alt"></i> Create your own level</a>
                        </div>
                        {% else %}
                            <div class="name">
                                {%  if nft.nft_link is not empty %}
                                <a href="{{ nft.nft_link }}" target="_blank">
                                    {% if nft.level_order < 999998 %}
                                        #{{ nft.level_order }} -
                                    {% endif %}
                                    {{ nft.title|e }}
                                </a>
                                {% else %}
                                    {% if nft.level_order < 999998 %}
                                        #{{ nft.level_order }} -
                                    {% endif %}
                                    {{ nft.title|e }}
                                {% endif %}
                            </div>
                            {%  if nft.artist_name is not empty %}
                                <div class="artist">
                                    Designed by
                                    {%  if nft.artist_link is not empty %}
                                        <a href="{{ nft.artist_link }}" target="_blank">
                                            {{ nft.artist_name|e }}
                                            {% if 'www.instagram.com' in nft.artist_link %}
                                                <i class="fab fa-instagram"></i>
                                            {% else %}
                                                <i class="fas fa-link"></i>
                                            {% endif %}
                                        </a>
                                    {%  else %}
                                        {{ nft.artist_name|e }}
                                    {%  endif %}
                                </div>
                            {% endif %}
                            {%  if nft.owner_name is not empty %}
                                <div class="owner">
                                    Owned by
                                    {%  if nft.owner_link is not empty %}
                                        <a href="{{ nft.owner_link }}" target="_blank">{{ nft.owner_name }}</a>
                                    {%  else %}
                                        {{ nft.owner_name }}
                                    {%  endif %}
                                </div>
                            {% endif %}
                            {%  if nft.nft_current_price is not empty %}
                                <div class="price">Price : {{ nft.nft_current_price / 1000000000000000000 }} ETH</div>
                            {% endif %}
                        {% endif %}
                    </div>
                </div>
                <div class="rocketHover"></div>
            </div>
        </div>
    {% endfor %}
    {% set nftsViewed = nftsViewed + 1 %}
    <div class="rocketBloc baseCryptokart" data-wow-delay="800ms" data-wow-duration="3000ms" style="z-index:1;"></div>
</div>
<div class="nftFooter"></div>
{{ partial('../Public/project') }}
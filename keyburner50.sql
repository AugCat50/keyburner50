-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Авг 10 2022 г., 21:13
-- Версия сервера: 10.4.18-MariaDB
-- Версия PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `keyburner50`
--

-- --------------------------------------------------------

--
-- Структура таблицы `default_texts`
--

CREATE TABLE `default_texts` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `text` longtext NOT NULL,
  `hidden` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `default_texts`
--

INSERT INTO `default_texts` (`id`, `name`, `text`, `hidden`) VALUES
(1, 'Этапы синтеза элементов', 'Для объяснения распространенности в природе различных химических элементов и их изотопов в 1948 году Гамовым была предложена модель Горячей Вселенной. По этой модели все химические элементы образовывались в момент Большого Взрыва. Однако это утверждение впоследствии было опровергнуто. Доказано, что только легкие элементы могли образоваться в момент Большого Взрыва, а более тяжелые возникли в процессах нуклеосинтеза. Эти положения сформулированы в модели Большого Взрыва', 0),
(2, 'Меркурий', 'Меркурий - ближайшая к Солнцу планета Солнечной системы, наименьшая из планет земной группы. Названа в честь древнеримского бога торговли - быстрого Меркурия, поскольку она движется по небу быстрее других планет. Её период обращения вокруг Солнца составляет всего 87,97 дней - самый короткий среди всех планет Солнечной системы.\r\nМеркурий относится к планетам земной группы. По своим физическим характеристикам Меркурий напоминает Луну. У него нет естественных спутников, но есть очень разрежённая атмосфера. Планета обладает крупным железным ядром, являющимся источником магнитного поля, напряжённость которого составляет 0,01 от земного магнитного поля. Ядро Меркурия составляет 83 % от всего объёма планеты. Температура на поверхности Меркурия колеблется от 80 до 700 К (от -190 до +430 С). Солнечная сторона нагревается гораздо больше, чем полярные области и обратная сторона планеты.\r\nО планете пока известно сравнительно немного.\r\n', 0),
(3, 'Венера', 'Венера — вторая по удалённости от Солнца планета Солнечной системы, наряду с Меркурием, Землёй и Марсом принадлежащая к семейству планет земной группы. Названа в честь древнеримской богини любви Венеры. По ряду характеристик — например, по массе и размерам — Венера считается «сестрой» Земли. Венерианский год составляет 224,7 земных суток.\r\nВенера имеет плотную атмосферу, состоящую более чем на 96 % из углекислого газа. Атмосферное давление на поверхности планеты в 92 раза больше, чем на поверхности Земли, и примерно равно давлению воды на глубине 900 метров. Венера — самая горячая планета в Солнечной системе: средняя температура её поверхности — 735 К (462 С), даже несмотря на то, что Меркурий находится ближе к Солнцу. Венера покрыта непрозрачным слоем облаков из серной кислоты с высокой отражающей способностью, что, помимо всего прочего, закрывает поверхность планеты от прямой видимости. Высокая температура поверхности обусловлена действием парникового эффекта.\r\n', 0),
(4, 'Земля', 'Земля — третья по удалённости от Солнца планета Солнечной системы. Самая плотная, пятая по диаметру и массе среди всех планет и крупнейшая среди планет земной группы, в которую входят также Меркурий, Венера и Марс. Единственное известное человеку на данный момент тело Солнечной системы в частности и Вселенной вообще, населённое живыми организмами.\r\nНаучные данные указывают на то, что Земля образовалась из солнечной туманности около 4,54 миллиарда лет назад и вскоре после этого обрела свой единственный естественный спутник — Луну. Жизнь, предположительно, появилась на Земле примерно 4,25 млрд лет назад, то есть вскоре после её возникновения. С тех пор биосфера Земли значительно изменила атмосферу и прочие абиотические факторы, обусловив количественный рост аэробных организмов, а также формирование озонового слоя, который вместе с магнитным полем Земли ослабляет вредную для жизни солнечную радиацию, тем самым сохраняя условия существования жизни на Земле. Радиация, обусловленная самой земной корой, со времён её образования значительно снизилась благодаря постепенному распаду радионуклидов, содержавшихся в ней. Кора Земли разделена на несколько сегментов, или тектонических плит, которые движутся по поверхности со скоростями порядка нескольких сантиметров в год. Изучением состава, строения и закономерностей развития Земли занимается наука геология.\r\n', 0),
(5, 'Цель статьи', 'Цель этой статьи не усложнить простые вещи, а акцентировать внимание на известных стандартах, о которых почему-то забывают.', 1),
(6, 'Жизнь и работа', 'Прежде чем начать делиться своими мыслями, хочу предупредить, что я не всезнайка. Успехом в жизни я во многом обязан умению справляться с тем, чего я не знаю. Самое важное, что я усвоил, – это подход к жизни на основе принципов, которые помогают мне понять, что правда, а что нет и как я должен поступать в той или иной ситуации.\r\nЯ рассказываю об этих принципах, потому что сейчас нахожусь на том жизненном этапе, когда больше хочу помочь другим стать успешными, чем стать более успешным самому.', 0),
(7, '10к безсмертных', 'Звук раздался неожиданно. Резкий, громкий одиночный \r\nвыстрел, совсем рядом. \r\nЛара знала этот звук. Внезапный испуг нарушил ее душевное \r\nравновесие. \r\nСердце бешено забилось в груди. Она чувствовала, как пот \r\nвыступил на лбу и между лопаток. Горло сдавило. Руки тряслись. \r\nЗакрывая книгу, она выронила ее на пол, и та была забыта. \r\n- Только не это! - произнесла Лара в пустоте комнаты. - \r\nНеужели снова. Я не хочу. \r\nОна встала и начала ходить по комнате. Попыталась \r\nсглотнуть, но в горле пересохло. Встряхнула дрожащие руки. В \r\nгруди невыносимо щемило. \r\n- Черт побери, Лара, дыши, - она ловила воздух. - Не позволяй себе... \r\nСлишком поздно. \r\nВот она снова на острове. Яматай. Вокруг бушует страшная \r\nбуря. Сверкают молнии. Раскаты грома. Пелена дождя, бьющего с \r\nужасной силой. Она пробирается сквозь джунгли. Сэм пропала. Все, о чем сейчас можно думать, это ее подруга. Она должна найти ее. \r\nКак только она приблизилась к монастырю, ветвистая молния \r\nосветила трупы десятков... сотен людей. Раскат грома пронесся над \r\nокеаном и ударился о горный пик прямо у Лары над головой, \r\nрассыпавшись вокруг нее, оглушив и оставив свежий запах озона в \r\nвоздухе. \r\nКогда звуковая волна прошла, Лара бросила быстрый взгляд \r\nна тела. Убиты недавно, все еще кровоточат, лежат на старых \r\nскелетных останках. Она старается не думать, как они встретили \r\nсвою смерть. Прошла между ними и вошла в монастырь. \r\nУ нее одна цель: спасти Сэм. \r\nЕще одна вспышка молнии разверзлась за ее спиной, на \r\nсекунду освещая часть древней постройки. Старые камни, \r\nизношенные годами. Капли крови блестят, как осколки красного \r\nстекла. Она делает вдох и идет дальше, из зала в зал. \r\nОна видела, как горели факелы в держателях на каменных \r\nстенах. Они отбрасывали мерцающий свет на плоть еще большего \r\nчисла тел, брошенных внутри монастыря Королевы Солнца, \r\nПимико... \r\nЛара закрыла глаза. Когда она их открыла, острова уже не \r\nбыло. Ее окружала тишина квартиры. \r\n- Черт, Лара, хватит туда возвращаться, - сказала она себе. - \r\nЯматай в прошлом. Это всего лишь воспоминания. \r\nОна часто дышала. Ее трясло и бросало в пот, тело пронзили \r\nозноб и слабость. Горло сжалось и она была в ужасе. \r\n- Ты знаешь, как это бывает. \r\nОна пыталась успокоиться. \r\n- Ты знаешь, как все проходит. Ты знаешь что делать. Вода. \r\nНужно взять бутылку с водой. \r\nЛара прошла в дальний конец своей лондонской квартиры-\r\nстудии, туда, где была кухня, и открыла холодильник. Она достала \r\nбутылку воды и попыталась открыть ее... дважды. Руки тряслись и \r\nкрышка не поддалась с первого раза. Наконец она смогла открыть \r\nбутылку, прислонила ее к губам и сделала глоток. Делая маленькие \r\nглотки, она надеялась восстановить контроль над дыханием. \r\n- Выдох, - сказала она себе. - Просто выдохни, Лара. \r\nОна ходила по квартире и пила воду. \r\n- Это был не выстрел, Лара, - сказала она. - Ты знаешь, что \r\nэто был не выстрел. \r\nПредложения вырывались короткими выдохами между \r\nглотками, пока она ходила по комнате. \r\n- Это был звук выхлопной трубы. \r\nОна продолжала ходить. \r\n- Все эта старая машина Бернарда. Почему он до сих пор на \r\nней ездит? \r\nОна выпила еще воды. \r\n- Дыши, Лара. \r\nПрошло пять минут. \r\nНаконец, она надела куртку, схватила ключи, телефон, и \r\nвышла из дома. \r\n- Уйди от этого, Лара, - сказала она, спускаясь по лестнице. \r\nНоги казались ватными и она не могла бежать вниз по \r\nступеням, как обычно. И не могла спуститься на лифте. Она никогда \r\nне ездила на лифте, предпочитая преодолевать три лестничных \r\nпролета в качестве упражнения. Вверх или вниз, неважно. Кроме \r\nтого, в разгар панической атаки замкунтое пространство лифта \r\nтолько усугубит положение. \r\nЛара Крофт считала, что ей повезло. Она была молода и \r\nсильна, в хорошей физической и умственной форме. Она лечилась \r\nот тревожного расстройства и знала, что с ней будет все в порядке. \r\nУ нее подозревали посттравматический стресс, но она отказалась \r\nпризнавать диагноз. Некоторые люди сильно страдали от этого \r\nзаболевания, они будут страдать всю свою жизнь и изменятся \r\nнавсегда. Она не собиралась становиться одной из них. \r\nПанические атаки были сильными, но она с ними справлялась. \r\nПреодолевала их. Ей помогали. Она была одной из тех, кому \r\nповезло. \r\nПрогулки были частью терапии, а в Лондоне достаточно \r\nхороших мест куда можно пойти. \r\nЛучшая подруга Лары, Сэм, снимала квартиру в центре Вест-\r\nЭнда, в театральном районе, и они жили там вместе. Независимо от \r\nвремени суток улицы всегда были освещены и переполнены. \r\nДевушки всегда были окружены суматохой жизни. Казалось, кафе, \r\nбары и рестораны никогда не закрывались, а люди не уходили. \r\nЛара шла уверенно, насколько это было возможно в ее \r\nсостоянии, пытаясь дышать, вдыхать воздух в легкие, чтобы \r\nсохранять устойчивость. Она пила воду и пыталась мыслить ясно. \r\nБыл поздний вечер и из театров после дневных сеансов начали \r\nвыходить люди. Магазины были все еще открыты, а на некоторых \r\nулицах стояли лотки, в которых можно было купить все, начиная от \r\nфруктов и овощей и заканчивая сувенирами и футболками. \r\nЛара продолжала идти в толпе слоняющихся людей. \r\nНикто ее не замечал. \r\nСначала прошла одышка, а затем и холодный пот. Сердце \r\nЛары билось почти нормально и в ногах появилась уверенность. \r\nРовное дыхание наконец позволило сделать большой и глубокий \r\nвдох. Она остановилась на углу улицы рядом с пабом, \r\nоблокотившись на фонарь, и вздохнула. Сделав последний глоток \r\nиз бутылки с водой, она выкинула ее в мусорку. \r\nЛара спрятала руки в карманы и перешла улицу. Пока она \r\nбыла не готова вернуться. \r\nОдним из важных моментов, которые Лара выучила о своей \r\nболезни, было то, что ей помогало, когда она представляла все не \r\nтаким важным. \r\nВсе просто. У нее случился приступ паники из-за внезапного \r\nшума, так как она не ожидала хлопка. Он звучал как выстрел. А ее \r\nразум ответил на это, вернувшись на Яматай. \r\nТо, что пережила Лара на острове Яматай, было жутким и \r\nпугающим. Она видела и делала вещи, которые до сих пор \r\nпреследовали ее. Сейчас, когда паника начала спадать, у нее \r\nпоявился шанс рационально все обдумать снова. \r\nОна жива. Выжила. Разрушила проклятье Пимико, чем бы оно \r\nни было. Лара уничтожила Королеву Солнца и спасла свою подругу. \r\nОна спасла жизнь Сэм. Только это было важно. \r\nОна спасла жизнь Сэм и сохранила свою. \r\nЛара Крофт осталась в живых. На Яматае она заплатила \r\nдорогую цену за возможность жить. Она застрелила человека. Ей \r\nпришлось убивать не единожды. У нее не было выбора. \r\nОна боролась, поэтому выжила. Если бы она сдалась, если бы \r\nне убивала, то Сэм была бы мертва. \r\nПрогуливаясь по улицам Вест-Энда, не замечая людей вокруг, \r\nЛара пыталась воспроизвести в голове некоторые события на \r\nЯматае. Она делала это не спеша, с определенной целью, не \r\nпозволяя возникать случайным образам в голове и не допуская \r\nмыслей, требующих большого внимания и способных взять над ней \r\nверх. Теперь все было у нее под контролем. \r\nОна изучила каждую ситуацию. Перебрала все возможные \r\nварианты, убедив себя в том, что приняла единственно возможные \r\nрешения, что ее поведение отвечало определенным \r\nобстоятельствам. \r\nВыживание - самый сильный инстинкт. \r\nЛара пережила последние моменты спасения Сэм снова. \r\nПодсчитала количество выстрелов, сделанных, чтобы защититься \r\nот Матиаса. Он бы убил ее. Он был готов убить ее и принести Сэм в \r\nжертву. Она продолжала стрелять, пока он не упал замертво. \r\nЗатем Лара встала между Королевой Солнца и своей \r\nподругой. Она взяла зажженный факел и вонзила его в сердце \r\nПимико... \r\nЛара почувствовала вибрацию в руке и ее пальцы плотно \r\nсжались, ладонь начала потеть. А через долю секунды зазвучал \r\nрингтон. \r\nЛара зажмурилась и сглотнула. Сделав глубокий вдох, она \r\nослабила хватку. \r\nВ ее руке больше нет факела. Она снова на улицах Лондона, \r\nсжимает в кармане мобильный телефон, который звонит. \r\nЛара тяжело выдохнула, вытащила телефон из кармана и \r\nпосмотрела на экран. Это была Сэм. Она успокоилась и ответила. \r\n- Привет, Сэм, - сказала она. - Я как раз о тебе думала. \r\nЕй ответил мужской голос. \r\n- Я говорю с Ларой Крофт? \r\n- Кто это? - спросила Лара. - Почему вы звоните с телефона \r\nСэм? \r\n- Мне нужно точно знать, что я говорю с Ларой Крофт. Я звоню \r\nпо поводу мисс Саманты Нисимура. Она попала в больницу.', 0),
(8, 'Земля', 'Второй текст по Земле', 0),
(9, 'Edited', 'Edited text', 1),
(10, 'Имя новый текст', 'Содержание Новый текст', 0),
(11, 'Шото ИО', 'Содержание Новый текст2', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `temps`
--

CREATE TABLE `temps` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key_act` varchar(128) NOT NULL,
  `mail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `temps`
--

INSERT INTO `temps` (`id`, `user_id`, `key_act`, `mail`) VALUES
(2, 2, '68ba1d0c53bfbf71bc3d7cb6c7b3fede20ed84d675c66525deae54a5ea13409f7d6fb7ffa802f4b4719056915b5d6cd2575655268fc491d7bff32e65545dc6be', '35a96ae6796c776e0506bdfcc0dc1459f0b31efa61052ad8b2313b68a63581acae37a5525c12de5f93dba6d0e6d27c552037');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `solt` tinyblob NOT NULL,
  `mail` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `solt`, `mail`) VALUES
(2, 'd88e0a00b84c24a7fefa15367c610af67a700c598f4d63842788cd75ddb41f33142b2782e9e1bafedd2f795c29004e17b6384a7b2fa52512e6a39436fe198961', '18f8ddf2e99412f7857d01f852f9ab394a4ff77b5571a6a375b919d6d9a1f1d77225f5cada6aff37c9229dd3ad901bca02338225d58ec31f07ae26f25191794a', 0x8033b0cd19633eb42eaddfce69977112a3ce47fa629cd3900033a8280e76b2bba8f3dadb0691f35bad36, '35a96ae6796c776e0506bdfcc0dc1459f0b31efa61052ad8b2313b68a63581acae37a5525c12de5f93dba6d0e6d27c552037d7f72256ab4866dde77429c23f51'),
(3, '5ba94290113465531bae7754af4ac873cfc173f3c86f90ae497ad87720fcf20fc83dd7e4ce0c1fb9985635385adb0ec04cdd7b0cc120c9b658171987c579e077', '0aea5da0f2d55eb0a7a3527305f9b9d078488bcdbcab31fe660c3fe43c1517aa5097962ee1abc0faa9ce7b7b24546d44083e72d6fb9ca803840a32c57c29ee2e', 0x010f8c225bef0afab71005722d2685dc672ffec6f9560758f2958476ae857f0943c25b282740, 'c0f43f60ddb3b958da1dbb8ae864eaa27773bc539ab9ab21a58fc954155858c12c09891ab195bb63355067f58d83794b5ccaf6f0562efaa4227c694cc9a952a4'),
(8, '46fb872d02de3f911e431cef6d062c53efd3a8b59ee0cd4d7499f07059f0d51131eb456465b0a3d93ca61645ee83bbfbd421a8b85973b6285f34f01386e8e406', '50acd528117999b1116db3a2bd4c02f418b4d57e86458381c243891b20566e057da5aff4bb471ee987560f49e3fbe988a576db553811c85fb63f49fc9dc42b61', 0xa061ff83a14413b96daf0100ac144a7fa189b6ef5aef77e63583eab468849ab1789deef5b737904db55f701e2f35297043df7270e883a48597da5c50db272df93f8853e6d36bc34552dd3173abe44b234984aa01ad84322f5d6b, '55b537c4ec7fe41e3dfeaa7247d7b592a4b20ee82fd66ab921b999ae47ba88a880670b706792303dd7144b61efd0a003a0cc657a95170e52b06c442b6481f60e'),
(17, '3a612b7213fd83cc4ee13b3faf0f774b2b6c156b2100bc8eabc3f3759a11e245524db9d1917c7b34df502a60fd8c66a96e37f4d8ad7a6fe3108f0dfe78d039e3', '12f5d572be0e2cf4e9f38d85d2fb449433a926f15b9934ed4b89d90a5c56ac79da8335170e8966421b3c6ce0448f25b074dcab81dd95f32f141b9aac3ee2255f', 0xf868f216c8a64cc574f152c0ca142e04ae2a75a98b6aafdfb21054f85fad4d3f0d72427a8a7b9716f74ce4b8017e7965512e45990a87a21578b659a1b2e3, '460d9fa10929673accf2d06a314d573d4b1f3be6ef9e0cebae28caf5aecb3a5e8edbef0aa4148eff07bc1cf345d852e141faa351a1f08ebfdbd19c62242764f5'),
(18, 'Вася1', 'password1', 0x39f41f24da8e5b205745, 'text@mail.test1'),
(19, 'Вася3', 'password3', 0xee531c5d37933450700c, 'text@mail.test3'),
(20, 'Вася1', 'password1', 0xa2316f5c4a277e0423ce, 'text@mail.test1'),
(21, 'Вася3', 'password3', 0x50258f4c7705f7e7640a, 'text@mail.test3'),
(22, 'Вася1', 'password1', 0x6fd71388f1c7146363e7, 'text@mail.test1'),
(23, 'Вася3', 'password3', 0x6cc237775cdfa6eb1c48, 'text@mail.test3'),
(24, 'Вася1', 'password1', 0x2e1cbfee2343af88d2c6, 'text@mail.test1'),
(25, 'Вася3', 'password3', 0x642fbde47fffadf38637, 'text@mail.test3');

-- --------------------------------------------------------

--
-- Структура таблицы `user_texts`
--

CREATE TABLE `user_texts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_themes` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `text` longtext NOT NULL,
  `statistics` longtext NOT NULL,
  `statistics_best` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `user_texts`
--

INSERT INTO `user_texts` (`id`, `user_id`, `user_themes`, `name`, `text`, `statistics`, `statistics_best`) VALUES
(1, 3, 1, 'Lorem Ipsum', 'Lorem Ipsum2 - это текст-\"рыба\", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной \"рыбой\" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.', '', '{3,-0,3}'),
(2, 2, 1, 'Изменённый имя текста', 'Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. Изменённый текст. ', '', '{3,-0,3}'),
(3, 2, 3, 'Edited text', 'Это текст 3', '{3,12.8.2021-141.753,12.8.2021-94.358,12.8.2021-94.358,3}', '{3,12.8.2021-141.753,3}'),
(4, 2, 3, 'new Name ИО', 'Это текст 4', '{3,12.8.2021-204.988,12.8.2021-204.988,12.8.2021-110.906,12.8.2021-110.906,3}', '{3,12.8.2021-204.988,3}'),
(22, 3, 1, 'йцук', 'йцук', '{3,12.8.2021-263.158,12.8.2021-172.538,3}', '{3,12.8.2021-263.158,3}'),
(49, 3, 17, 'Пиши, сокращай', 'Текст пишут все, не только писатели и журналисты. Менеджер в офисе, клерк в банке, учитель в школе, чиновник в мэрии — все что-нибудь пишут. Своим текстом они влияют на мир не меньше, чем писатели и журналисты.\nВыпускник вуза пишет письмо, чтобы устроиться на работу, — это текст. От того, насколько правильно он напишет это письмо, зависит начало карьеры: удастся ли ему поработать в интересной компании или придется перебиваться низкооплачиваемым трудом, как его сверстникам.\nПредприниматель составляет договор — это тоже текст. Если по этому договору его привлекут к суду, судить будут по тексту.\nОт того, насколько ясно составлен договор, зависит судьба предприятия, его сотрудников и их семей.\nМэрия перекопала полгорода, чтобы отреставрировать центральные улицы. Жители негодуют: тракторы мешают им проехать.\nПресс-центр мэрии рассказывает людям через СМИ, зачем нужна реконструкция и как будет хорошо после нее. Если они смогут объяснить это простым и понятным языком, жители их поддержат и не будут возмущаться.\nПреподаватель пишет учебник по экономике. Учебник попадает в школу. От того, какой в этом учебнике текст, зависит экономическая грамотность школьников. Кто-то из этих школьников станет министром экономики.', '', ''),
(52, 3, 1, 'Новый', 'Lorem Ipsum2 - это текст-\"рыба\", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной \"рыбой\" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн.\nЕго популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum.', '', ''),
(53, 3, 1, 'паипа', 'пипаиапи пкркерк', '', ''),
(54, 3, 17, 'gfnhg', 'fgngfh ggrthrt', '', ''),
(55, 3, 3, 'erfrefer', 'rferfer trghrt', '', ''),
(56, 3, 17, 'bgfb 1', 'fgbfgbfg 1111 222', '', ''),
(60, 3, 19, 'we', 'we', '', ''),
(61, 17, 20, 'Первый текст', 'Первый текст', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `user_themes`
--

CREATE TABLE `user_themes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `user_themes`
--

INSERT INTO `user_themes` (`id`, `user_id`, `name`) VALUES
(1, 3, 'Имя 2'),
(2, 1, 'Тема 1 пользователя 1'),
(3, 3, 'Новый текст'),
(4, 1, 'Содержание Новый текст2'),
(17, 3, 'Разные'),
(19, 3, 'hnh'),
(20, 17, 'Новая тема');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `default_texts`
--
ALTER TABLE `default_texts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `temps`
--
ALTER TABLE `temps`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_texts`
--
ALTER TABLE `user_texts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_themes`
--
ALTER TABLE `user_themes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `default_texts`
--
ALTER TABLE `default_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `temps`
--
ALTER TABLE `temps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `user_texts`
--
ALTER TABLE `user_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT для таблицы `user_themes`
--
ALTER TABLE `user_themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
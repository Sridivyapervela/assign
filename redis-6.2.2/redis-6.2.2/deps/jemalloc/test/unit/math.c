#include "test/jemalloc_test.h"

#define MAX_REL_ERR 1.0e-9
#define MAX_ABS_ERR 1.0e-9

#include <float.h>

#ifdef __PGI
#undef INFINITY
#endif

#ifndef INFINITY
#define INFINITY (DBL_MAX + DBL_MAX)
#endif

static bool
double_eq_rel(double a, double b, double max_rel_err, double max_abs_err) {
	double rel_err;

	if (fabs(a - b) < max_abs_err) {
		return true;
	}
	rel_err = (fabs(b) > fabs(a)) ? fabs((a-b)/b) : fabs((a-b)/a);
	return (rel_err < max_rel_err);
}

static uint64_t
factorial(unsigned x) {
	uint64_t ret = 1;
	unsigned i;

	for (i = 2; i <= x; i++) {
		ret *= (uint64_t)i;
	}

	return ret;
}

TEST_BEGIN(test_ln_gamma_factorial) {
	unsigned x;

	/* exp(ln_gamma(x)) == (x-1)! for integer x. */
	for (x = 1; x <= 21; x++) {
		assert_true(double_eq_rel(exp(ln_gamma(x)),
		    (double)factorial(x-1), MAX_REL_ERR, MAX_ABS_ERR),
		    "Incorrect factorial result for x=%u", x);
	}
}
TEST_END

/* Expected ln_gamma([0.0..100.0] increment=0.25). */
static const double ln_gamma_misc_expected[] = {
	INFINITY,
	1.28802252469807743, 0.57236494292470008, 0.20328095143129538,
	0.00000000000000000, -0.09827183642181320, -0.12078223763524518,
	-0.08440112102048555, 0.00000000000000000, 0.12487171489239651,
	0.28468287047291918, 0.47521466691493719, 0.69314718055994529,
	0.93580193110872523, 1.20097360234707429, 1.48681557859341718,
	1.79175946922805496, 2.11445692745037128, 2.45373657084244234,
	2.80857141857573644, 3.17805383034794575, 3.56137591038669710,
	3.95781396761871651, 4.36671603662228680, 4.78749174278204581,
	5.21960398699022932, 5.66256205985714178, 6.11591589143154568,
	6.57925121201010121, 7.05218545073853953, 7.53436423675873268,
	8.02545839631598312, 8.52516136106541467, 9.03318691960512332,
	9.54926725730099690, 10.07315123968123949, 10.60460290274525086,
	11.14340011995171231, 11.68933342079726856, 12.24220494005076176,
	12.80182748008146909, 13.36802367147604720, 13.94062521940376342,
	14.51947222506051816, 15.10441257307551943, 15.69530137706046524,
	16.29200047656724237, 16.89437797963419285, 17.50230784587389010,
	18.11566950571089407, 18.73434751193644843, 19.35823122022435427,
	19.98721449566188468, 20.62119544270163018, 21.26007615624470048,
	21.90376249182879320, 22.55216385312342098, 23.20519299513386002,
	23.86276584168908954, 24.52480131594137802, 25.19122118273868338,
	25.86194990184851861, 26.53691449111561340, 27.21604439872720604,
	27.89927138384089389, 28.58652940490193828, 29.27775451504081516,
	29.97288476399884871, 30.67186010608067548, 31.37462231367769050,
	32.08111489594735843, 32.79128302226991565, 33.50507345013689076,
	34.22243445715505317, 34.94331577687681545, 35.66766853819134298,
	36.39544520803305261, 37.12659953718355865, 37.86108650896109395,
	38.59886229060776230, 39.33988418719949465, 40.08411059791735198,
	40.83150097453079752, 41.58201578195490100, 42.33561646075348506,
	43.09226539146988699, 43.85192586067515208, 44.61456202863158893,
	45.38013889847690052, 46.14862228684032885, 46.91997879580877395,
	47.69417578616628361, 48.47118135183522014, 49.25096429545256882,
	50.03349410501914463, 50.81874093156324790, 51.60667556776436982,
	52.39726942748592364, 53.19049452616926743, 53.98632346204390586,
	54.78472939811231157, 55.58568604486942633, 56.38916764371992940,
	57.19514895105859864, 58.00360522298051080, 58.81451220059079787,
	59.62784609588432261, 60.44358357816834371, 61.26170176100199427,
	62.08217818962842927, 62.90499082887649962, 63.73011805151035958,
	64.55753862700632340, 65.38723171073768015, 66.21917683354901385,
	67.05335389170279825, 67.88974313718154008, 68.72832516833013017,
	69.56908092082363737, 70.41199165894616385, 71.25703896716800045,
	72.10420474200799390, 72.95347118416940191, 73.80482079093779646,
	74.65823634883015814, 75.51370092648485866, 76.37119786778275454,
	77.23071078519033961, 78.09222355331530707, 78.95572030266725960,
	79.82118541361435859, 80.68860351052903468, 81.55795945611502873,
	82.42923834590904164, 83.30242550295004378, 84.17750647261028973,
	85.05446701758152983, 85.93329311301090456, 86.81397094178107920,
	87.69648688992882057, 88.58082754219766741, 89.46697967771913795,
	90.35493026581838194, 91.24466646193963015, 92.13617560368709292,
	93.02944520697742803, 93.92446296229978486, 94.82121673107967297,
	95.71969454214321615, 96.61988458827809723, 97.52177522288820910,
	98.42535495673848800, 99.33061245478741341, 100.23753653310367895,
	101.14611615586458981, 102.05634043243354370, 102.96819861451382394,
	103.88168009337621811, 104.79677439715833032, 105.71347118823287303,
	106.63176026064346047, 107.55163153760463501, 108.47307506906540198,
	109.39608102933323153, 110.32063971475740516, 111.24674154146920557,
	112.17437704317786995, 113.10353686902013237, 114.03421178146170689,
	114.96639265424990128, 115.90007047041454769, 116.83523632031698014,
	117.77188139974506953, 118.70999700805310795, 119.64957454634490830,
	120.59060551569974962, 121.53308151543865279, 122.47699424143097247,
	123.42233548443955726, 124.36909712850338394, 125.31727114935689826,
	126.26684961288492559, 127.21782467361175861, 128.17018857322420899,
	129.12393363912724453, 130.07905228303084755, 131.03553699956862033,
	131.99338036494577864, 132.95257503561629164, 133.91311374698926784,
	134.87498931216194364, 135.83819462068046846, 136.80272263732638294,
	137.76856640092901785, 138.73571902320256299, 139.70417368760718091,
	140.67392364823425055, 141.64496222871400732, 142.61728282114600574,
	143.59087888505104047, 144.56574394634486680, 145.54187159633210058,
	146.51925549072063859, 147.49788934865566148, 148.47776695177302031,
	149.45888214327129617, 150.44122882700193600, 151.42480096657754984,
	152.40959258449737490, 153.39559776128982094, 154.38281063467164245,
	155.37122539872302696, 156.36083630307879844, 157.35163765213474107,
	158.34362380426921391, 159.33678917107920370, 160.33112821663092973,
	161.32663545672428995, 162.32330545817117695, 163.32113283808695314,
	164.32011226319519892, 165.32023844914485267, 166.32150615984036790,
	167.32391020678358018, 168.32744544842768164, 169.33210678954270634,
	170.33788918059275375, 171.34478761712384198, 172.35279713916281707,
	173.36191283062726143, 174.37212981874515094, 175.38344327348534080,
	176.39584840699734514, 177.40934047306160437, 178.42391476654847793,
	179.43956662288721304, 180.45629141754378111, 181.47408456550741107,
	182.49294152078630304, 183.51285777591152737, 184.53382886144947861,
	185.55585034552262869, 186.57891783333786861, 187.60302696672312095,
	188.62817342367162610, 189.65435291789341932, 190.68156119837468054,
	191.70979404894376330, 192.73904728784492590, 193.76931676731820176,
	194.80059837318714244, 195.83288802445184729, 196.86618167288995096,
	197.90047530266301123, 198.93576492992946214, 199.97204660246373464,
	201.00931639928148797, 202.04757043027063901, 203.08680483582807597,
	204.12701578650228385, 205.16819948264117102, 206.21035215404597807,
	207.25347005962987623, 208.29754948708190909, 209.34258675253678916,
	210.38857820024875878, 211.43552020227099320, 212.48340915813977858,
	213.53224149456323744, 214.58201366511514152, 215.63272214993284592,
	216.68436345542014010, 217.73693411395422004, 218.79043068359703739,
	219.84484974781133815, 220.90018791517996988, 221.95644181913033322,
	223.01360811766215875, 224.07168349307951871, 225.13066465172661879,
	226.19054832372759734, 227.25133126272962159, 228.31301024565024704,
	229.37558207242807384, 230.43904356577689896, 231.50339157094342113,
	232.56862295546847008, 233.63473460895144740, 234.70172344281823484,
	235.76958639009222907, 236.83832040516844586, 237.90792246359117712,
	238.97838956183431947, 240.04971871708477238, 241.12190696702904802,
	242.19495136964280846, 243.26884900298270509, 244.34359696498191283,
	245.41919237324782443, 246.49563236486270057, 247.57291409618682110,
	248.65103474266476269, 249.72999149863338175, 250.80978157713354904,
	251.89040220972316320, 252.97185064629374551, 254.05412415488834199,
	255.13722002152300661, 256.22113555000953511, 257.30586806178126835,
	258.39141489572085675, 259.47777340799029844, 260.56494097186322279,
	261.65291497755913497, 262.74169283208021852, 263.83127195904967266,
	264.92164979855277807, 266.01282380697938379, 267.10479145686849733,
	268.19755023675537586, 269.29109765101975427, 270.38543121973674488,
	271.48054847852881721, 272.57644697842033565, 273.67312428569374561,
	274.77057798174683967, 275.86880566295326389, 276.96780494052313770,
	278.06757344036617496, 279.16810880295668085, 280.26940868320008349,
	281.37147075030043197, 282.47429268763045229, 283.57787219260217171,
	284.68220697654078322, 285.78729476455760050, 286.89313329542699194,
	287.99972032146268930, 289.10705360839756395, 290.21513093526289140,
	291.32395009427028754, 292.43350889069523646, 293.54380514276073200,
	294.65483668152336350, 295.76660135076059532, 296.87909700685889902,
	297.99232151870342022, 299.10627276756946458, 300.22094864701409733,
	301.33634706277030091, 302.45246593264130297, 303.56930318639643929,
	304.68685676566872189, 305.80512462385280514, 306.92410472600477078,
	308.04379504874236773, 309.16419358014690033, 310.28529831966631036,
	311.40710727801865687, 312.52961847709792664, 313.65282994987899201,
	314.77673974032603610, 315.90134590329950015, 317.02664650446632777,
	318.15263962020929966, 319.27932333753892635, 320.40669575400545455,
	321.53475497761127144, 322.66349912672620803, 323.79292633000159185,
	324.92303472628691452, 326.05382246454587403, 327.18528770377525916,
	328.31742861292224234, 329.45024337080525356, 330.58373016603343331,
	331.71788719692847280, 332.85271267144611329, 333.98820480709991898,
	335.12436183088397001, 336.26118197919845443, 337.39866349777429377,
	338.53680464159958774, 339.67560367484657036, 340.81505887079896411,
	341.95516851178109619, 343.09593088908627578, 344.23734430290727460,
	345.37940706226686416, 346.52211748494903532, 347.66547389743118401,
	348.80947463481720661, 349.95411804077025408, 351.09940246744753267,
	352.24532627543504759, 353.39188783368263103, 354.53908551944078908,
	355.68691771819692349, 356.83538282361303118, 357.98447923746385868,
	359.13420536957539753
};

TEST_BEGIN(test_ln_gamma_misc) {
	unsigned i;

	for (i = 1; i < sizeof(ln_gamma_misc_expected)/sizeof(double); i++) {
		double x = (double)i * 0.25;
		assert_true(double_eq_rel(ln_gamma(x),
		    ln_gamma_misc_expected[i], MAX_REL_ERR, MAX_ABS_ERR),
		    "Incorrect ln_gamma result for i=%u", i);
	}
}
TEST_END

/* Expected pt_norm([0.01..0.99] increment=0.01). */
static const double pt_norm_expected[] = {
	-INFINITY,
	-2.32634787404084076, -2.05374891063182252, -1.88079360815125085,
	-1.75068607125216946, -1.64485362695147264, -1.55477359459685305,
	-1.47579102817917063, -1.40507156030963221, -1.34075503369021654,
	-1.28155156554460081, -1.22652812003661049, -1.17498679206608991,
	-1.12639112903880045, -1.08031934081495606, -1.03643338949378938,
	-0.99445788320975281, -0.95416525314619416, -0.91536508784281390,
	-0.87789629505122846, -0.84162123357291418, -0.80642124701824025,
	-0.77219321418868492, -0.73884684918521371, -0.70630256284008752,
	-0.67448975019608171, -0.64334540539291685, -0.61281299101662701,
	-0.58284150727121620, -0.55338471955567281, -0.52440051270804067,
	-0.49585034734745320, -0.46769879911450812, -0.43991316567323380,
	-0.41246312944140462, -0.38532046640756751, -0.35845879325119373,
	-0.33185334643681652, -0.30548078809939738, -0.27931903444745404,
	-0.25334710313579978, -0.22754497664114931, -0.20189347914185077,
	-0.17637416478086135, -0.15096921549677725, -0.12566134685507399,
	-0.10043372051146975, -0.07526986209982976, -0.05015358346473352,
	-0.02506890825871106, 0.00000000000000000, 0.02506890825871106,
	0.05015358346473366, 0.07526986209982990, 0.10043372051146990,
	0.12566134685507413, 0.15096921549677739, 0.17637416478086146,
	0.20189347914185105, 0.22754497664114931, 0.25334710313579978,
	0.27931903444745404, 0.30548078809939738, 0.33185334643681652,
	0.35845879325119373, 0.38532046640756762, 0.41246312944140484,
	0.43991316567323391, 0.46769879911450835, 0.49585034734745348,
	0.52440051270804111, 0.55338471955567303, 0.58284150727121620,
	0.61281299101662701, 0.64334540539291685, 0.67448975019608171,
	0.70630256284008752, 0.73884684918521371, 0.77219321418868492,
	0.80642124701824036, 0.84162123357291441, 0.87789629505122879,
	0.91536508784281423, 0.95416525314619460, 0.99445788320975348,
	1.03643338949378938, 1.08031934081495606, 1.12639112903880045,
	1.17498679206608991, 1.22652812003661049, 1.28155156554460081,
	1.34075503369021654, 1.40507156030963265, 1.47579102817917085,
	1.55477359459685394, 1.64485362695147308, 1.75068607125217102,
	1.88079360815125041, 2.05374891063182208, 2.32634787404084076
};

TEST_BEGIN(test_pt_norm) {
	unsigned i;

	for (i = 1; i < sizeof(pt_norm_expected)/sizeof(double); i++) {
		double p = (double)i * 0.01;
		assert_true(double_eq_rel(pt_norm(p), pt_norm_expected[i],
		    MAX_REL_ERR, MAX_ABS_ERR),
		    "Incorrect pt_norm result for i=%u", i);
	}
}
TEST_END

/*
 * Expected pt_chi2(p=[0.01..0.99] increment=0.07,
 *                  df={0.1, 1.1, 10.1, 100.1, 1000.1}).
 */
static const double pt_chi2_df[] = {0.1, 1.1, 10.1, 100.1, 1000.1};
static const double pt_chi2_expected[] = {
	1.168926411457320e-40, 1.347680397072034e-22, 3.886980416666260e-17,
	8.245951724356564e-14, 2.068936347497604e-11, 1.562561743309233e-09,
	5.459543043426564e-08, 1.114775688149252e-06, 1.532101202364371e-05,
	1.553884683726585e-04, 1.239396954915939e-03, 8.153872320255721e-03,
	4.631183739647523e-02, 2.473187311701327e-01, 2.175254800183617e+00,

	0.0003729887888876379, 0.0164409238228929513, 0.0521523015190650113,
	0.1064701372271216612, 0.1800913735793082115, 0.2748704281195626931,
	0.3939246282787986497, 0.5420727552260817816, 0.7267265822221973259,
	0.9596554296000253670, 1.2607440376386165326, 1.6671185084541604304,
	2.2604828984738705167, 3.2868613342148607082, 6.9298574921692139839,

	2.606673548632508, 4.602913725294877, 5.646152813924212,
	6.488971315540869, 7.249823275816285, 7.977314231410841,
	8.700354939944047, 9.441728024225892, 10.224338321374127,
	11.076435368801061, 12.039320937038386, 13.183878752697167,
	14.657791935084575, 16.885728216339373, 23.361991680031817,

	70.14844087392152, 80.92379498849355, 85.53325420085891,
	88.94433120715347, 91.83732712857017, 94.46719943606301,
	96.96896479994635, 99.43412843510363, 101.94074719829733,
	104.57228644307247, 107.43900093448734, 110.71844673417287,
	114.76616819871325, 120.57422505959563, 135.92318818757556,

	899.0072447849649, 937.9271278858220, 953.8117189560207,
	965.3079371501154, 974.8974061207954, 983.4936235182347,
	991.5691170518946, 999.4334123954690, 1007.3391826856553,
	1015.5445154999951, 1024.3777075619569, 1034.3538789836223,
	1046.4872561869577, 1063.5717461999654, 1107.0741966053859
};

TEST_BEGIN(test_pt_chi2) {
	unsigned i, j;
	unsigned e = 0;

	for (i = 0; i < sizeof(pt_chi2_df)/sizeof(double); i++) {
		double df = pt_chi2_df[i];
		double ln_gamma_df = ln_gamma(df * 0.5);
		for (j = 1; j < 100; j += 7) {
			double p = (double)j * 0.01;
			assert_true(double_eq_rel(pt_chi2(p, df, ln_gamma_df),
			    pt_chi2_expected[e], MAX_REL_ERR, MAX_ABS_ERR),
			    "Incorrect pt_chi2 result for i=%u, j=%u", i, j);
			e++;
		}
	}
}
TEST_END

/*
 * Expected pt_gamma(p=[0.1..0.99] increment=0.07,
 *                   shape=[0.5..3.0] increment=0.5).
 */
static const double pt_gamma_shape[] = {0.5, 1.0, 1.5, 2.0, 2.5, 3.0};
static const double pt_gamma_expected[] = {
	7.854392895485103e-05, 5.043466107888016e-03, 1.788288957794883e-02,
	3.900956150232906e-02, 6.913847560638034e-02, 1.093710833465766e-01,
	1.613412523825817e-01, 2.274682115597864e-01, 3.114117323127083e-01,
	4.189466220207417e-01, 5.598106789059246e-01, 7.521856146202706e-01,
	1.036125427911119e+00, 1.532450860038180e+00, 3.317448300510606e+00,

	0.01005033585350144, 0.08338160893905107, 0.16251892949777497,
	0.24846135929849966, 0.34249030894677596, 0.44628710262841947,
	0.56211891815354142, 0.69314718055994529, 0.84397007029452920,
	1.02165124753198167, 1.23787435600161766, 1.51412773262977574,
	1.89711998488588196, 2.52572864430825783, 4.60517018598809091,

	0.05741590094955853, 0.24747378084860744, 0.39888572212236084,
	0.54394139997444901, 0.69048812513915159, 0.84311389861296104,
	1.00580622221479898, 1.18298694218766931, 1.38038096305861213,
	1.60627736383027453, 1.87396970522337947, 2.20749220408081070,
	2.65852391865854942, 3.37934630984842244, 5.67243336507218476,

	0.1485547402532659, 0.4657458011640391, 0.6832386130709406,
	0.8794297834672100, 1.0700752852474524, 1.2629614217350744,
	1.4638400448580779, 1.6783469900166610, 1.9132338090606940,
	2.1778589228618777, 2.4868823970010991, 2.8664695666264195,
	3.3724415436062114, 4.1682658512758071, 6.6383520679938108,

	0.2771490383641385, 0.7195001279643727, 0.9969081732265243,
	1.2383497880608061, 1.4675206597269927, 1.6953064251816552,
	1.9291243435606809, 2.1757300955477641, 2.4428032131216391,
	2.7406534569230616, 3.0851445039665513, 3.5043101122033367,
	4.0575997065264637, 4.9182956424675286, 7.5431362346944937,

	0.4360451650782932, 0.9983600902486267, 1.3306365880734528,
	1.6129750834753802, 1.8767241606994294, 2.1357032436097660,
	2.3988853336865565, 2.6740603137235603, 2.9697561737517959,
	3.2971457713883265, 3.6731795898504660, 4.1275751617770631,
	4.7230515633946677, 5.6417477865306020, 8.4059469148854635
};

TEST_BEGIN(test_pt_gamma_shape) {
	unsigned i, j;
	unsigned e = 0;

	for (i = 0; i < sizeof(pt_gamma_shape)/sizeof(double); i++) {
		double shape = pt_gamma_shape[i];
		double ln_gamma_shape = ln_gamma(shape);
		for (j = 1; j < 100; j += 7) {
			double p = (double)j * 0.01;
			assert_true(double_eq_rel(pt_gamma(p, shape, 1.0,
			    ln_gamma_shape), pt_gamma_expected[e], MAX_REL_ERR,
			    MAX_ABS_ERR),
			    "Incorrect pt_gamma result for i=%u, j=%u", i, j);
			e++;
		}
	}
}
TEST_END

TEST_BEGIN(test_pt_gamma_scale) {
	double shape = 1.0;
	double ln_gamma_shape = ln_gamma(shape);

	assert_true(double_eq_rel(
	    pt_gamma(0.5, shape, 1.0, ln_gamma_shape) * 10.0,
	    pt_gamma(0.5, shape, 10.0, ln_gamma_shape), MAX_REL_ERR,
	    MAX_ABS_ERR),
	    "Scale should be trivially equivalent to external multiplication");
}
TEST_END

int
main(void) {
	return test(
	    test_ln_gamma_factorial,
	    test_ln_gamma_misc,
	    test_pt_norm,
	    test_pt_chi2,
	    test_pt_gamma_shape,
	    test_pt_gamma_scale);
}
